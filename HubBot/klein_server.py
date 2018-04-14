import logging

from rasa_core.agent import Agent
from rasa_core.channels.direct import CollectingOutputChannel
from rasa_core.interpreter import RasaNLUInterpreter

from filter import intent_status
import yaml
from flask import json
from klein import Klein

logger = logging.getLogger(__name__)

checkFirstRequest = 0


def read_yaml():
    global templates
    with open("domain.yml", 'r') as stream:
        try:
            data = yaml.load(stream)
            templates = data.get('templates')
        except yaml.YAMLError as exc:
            print(exc)
    return templates


def intent_list(data):
    intent_array = []
    intents = []
    for d in data:
        intents.append(d.get('name'))
    for intent in intents:
        if "utter_" + intent in templates.keys():
            intent_array.append({"name": intent, "utter": templates.get("utter_" + intent)[0]})
    return intent_array


def custom_filter(query, parsed_data, respond_data):
    global check_intent, confidence_score_array, low_confidence_response, check_first_request, query_to_add, file_name
    if check_first_request == 0:
        check_intent = 0
    check_first_request = check_first_request + 1
    intent_name = parsed_data.get('tracker').get('latest_message').get('intent').get('name')
    confidence = parsed_data.get('tracker').get('latest_message').get('intent').get('confidence') * 100
    if confidence < 20 or (intent_name == "confirmation.no" and check_intent == 1):
        if check_intent != 1:
            data = parsed_data.get('tracker').get('latest_message').get('intent_ranking')
            confidence_score_array = intent_list(data)
            confidence_score_array = confidence_score_array[::-1]
            query_to_add = query
        check_intent = 1
        low_confidence_response = confidence_score_array.pop()
        file_name = low_confidence_response.get('name')
        low_confidence_response = [low_confidence_response.get('utter'), "Did i give you the right response?"]
        return low_confidence_response
    elif confidence < 20 or (intent_name == "confirmation.yes" and check_intent == 1):
        intent_status(query_to_add, file_name)
        confidence_score_array = []
        check_intent = 0
        msg = ["I will keep that in mind"]
        return msg
    else:
        check_intent = 0
        return respond_data


def low_confidence_filter(query, sender_id, parsed_data, respond_data):
    global intent_to_add, data_to_add
    intent_name = parsed_data.get('tracker').get('latest_message').get('intent').get('name')
    confidence = parsed_data.get('tracker').get('latest_message').get('intent').get('confidence') * 100
    entities = parsed_data.get('tracker').get('latest_message').get('entities')
    try:
        status = users.get_user(sender_id)[0].get('status')
    except IndexError:
        users.add_user(sender_id, 0)
        status = 0
    if confidence < 20 or (intent_name == "confirmation.yes" and status == 1) or (
            intent_name == "confirmation.no" and status == 1):

        if intent_name == "confirmation.yes" and status == 1:
            users.remove_user(sender_id)
            intent_status(data_to_add, intent_to_add)
            return ["I will keep that in mind. Thank you for your response"]
        elif intent_name == "confirmation.no" and status == 1:
            users.remove_user(sender_id)
            return ["I will let my developers know about it, thank you for your response"]
        else:
            if not respond_data:
                respond_data.append("Sorry, I couldn't understand you, can you ask me in another way?")
            else:
                entity_list = []
                if entities:
                    for entity in entities:
                        entity_list.append({"entity": entity.get("entity"), "entity_value": entity.get("value")})
                intent_to_add = intent_name
                data_to_add = {"text": query, "entities": entity_list}
                respond_data.append("Did i give you the right response?")
            users.update_status(sender_id, 1)
            return respond_data
    else:
        if status == 1:
            users.remove_user(sender_id)
        if not respond_data:
            respond_data.append("Sorry, I couldn't understand you, can you ask me in another way?")
        return respond_data


def request_parameters(request):
    if request.method.decode('utf-8', 'strict') == 'GET':
        return {
            key.decode('utf-8', 'strict'): value[0].decode('utf-8',
                                                           'strict')
            for key, value in request.args.items()}
    else:
        content = request.content.read()
        try:
            return json.loads(content.decode('utf-8', 'strict'))
        except ValueError as e:
            logger.error("Failed to decode json during respond request. "
                         "Error: {}. Request content: "
                         "'{}'".format(e, content))
            raise


class ConfusedUsers:
    def __init__(self):
        self.users = []

    def add_user(self, sender_id, status):
        self.users.append(dict({"sender_id": sender_id, "status": status}))

    def get_user(self, sender_id):
        return [user for user in self.users if user['sender_id'] == sender_id]

    def remove_user(self, sender_id):
        self.users = [user for user in self.users if user['sender_id'] != sender_id]

    def update_status(self, sender_id, status):
        for user in self.users:
            if user.get('sender_id') == sender_id:
                user.update(dict({'sender_id': sender_id, 'status': status}))


class FilterServer:
    app = Klein()

    def __init__(self, model_directory, interpreter=None):
        self.model_directory = model_directory
        self.interpreter = interpreter
        self.agent = self._create_agent(model_directory, interpreter)

    # noinspection PyDeprecation
    @staticmethod
    def _create_agent(model_directory, interpreter):
        try:

            return Agent.load(model_directory, interpreter)
        except Exception as e:
            logger.warn("Failed to load any agent model. Running "
                        "Rasa Core server with out loaded model now. {}"
                        "".format(e))
            return None

    @app.route("/api/v1/status", methods=['GET'])
    def status(self, request):
        """Check if the server is running and responds with the status."""
        request.setHeader('Access-Control-Allow-Origin', '*')
        return json.dumps({'status': 'OK'})

    @app.route('/api/v1/<sender_id>/parse', methods=['GET', 'POST'])
    def parse(self, request, sender_id):
        request.setHeader('Content-Type', 'application/json')
        request_params = request_parameters(request)

        if 'query' in request_params:
            message = request_params.pop('query')
        elif 'q' in request_params:
            message = request_params.pop('q')
        else:
            request.setResponseCode(400)
            return json.dumps({"error": "Invalid parse parameter specified"})
        try:
            response = self.agent.start_message_handling(message, sender_id)
            request.setResponseCode(200)
            return json.dumps(response)
        except Exception as e:
            request.setResponseCode(500)
            logger.error("Caught an exception during "
                         "parse: {}".format(e), exc_info=1)
            return json.dumps({"error": "{}".format(e)})

    @app.route('/api/v1/<sender_id>/respond', methods=['GET', 'POST'])
    def respond(self, request, sender_id):
        request.setHeader('Content-Type', 'application/json')
        request.setHeader('Access-Control-Allow-Origin', '*')
        request_params = request_parameters(request)
        if 'query' in request_params:
            message = request_params.pop('query')
        elif 'q' in request_params:
            message = request_params.pop('q')
        else:
            request.setResponseCode(400)
            return json.dumps({"error": "Invalid parse parameter specified"})
        try:
            parse_data = self.agent.start_message_handling(message, sender_id)
            out = CollectingOutputChannel()
            response_data = self.agent.handle_message(message, output_channel=out, sender_id=sender_id)
            response = low_confidence_filter(message, sender_id, parse_data, response_data)
            request.setResponseCode(200)
            return json.dumps(response)
        except Exception as e:
            request.setResponseCode(500)
            logger.error("Caught an exception during "
                         "parse: {}".format(e), exc_info=1)
            return json.dumps({"error": "{}".format(e)})


if __name__ == "__main__":
    read_yaml()
    users = ConfusedUsers()
    filter_object = FilterServer("models/dialogue/", RasaNLUInterpreter("models/nlu/default"
                                                                                              "/nlu_model"))
    logger.info("Started http server on port %s" % 8081)
    filter_object.app.run("0.0.0.0", 8081)
