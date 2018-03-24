from __future__ import absolute_import
from __future__ import division
from __future__ import print_function
from __future__ import unicode_literals

import logging

from flask_cors import CORS, cross_origin
from flask import Blueprint, request, jsonify
from rasa_core.agent import Agent
from rasa_core.channels import HttpInputChannel
from rasa_core.channels.channel import UserMessage
from rasa_core.channels.direct import CollectingOutputChannel
from rasa_core.channels.rest import HttpInputComponent
from rasa_core.interpreter import RasaNLUInterpreter


logger = logging.getLogger(__name__)


class SimpleWebBot(HttpInputComponent):
    """A simple web bot that listens on a url and responds."""

    def blueprint(self, on_new_message):
        app = Blueprint('app', __name__)
        
        CORS(app)
        logging.getLogger('flask_cors').level = logging.DEBUG

        @app.route("/status", methods=['GET'])
        def health():
            return jsonify({"status": "ok"})

        @app.route("/respond", methods=['GET', 'POST'])
        @cross_origin()
        def respond():
            text = request.args.get('q')
            sender_id = request.args.get('id')
            out = CollectingOutputChannel()
            on_new_message(UserMessage(text, out, sender_id))
            responses = [m for _, m in out.messages]
            return jsonify(responses)

        return app


def run(serve_forever=True):
    # path to your NLU model
    interpreter = RasaNLUInterpreter("models/nlu/default/wordsnlu")
    # path to your dialogues models
    agent = Agent.load("models/dialogue", interpreter=interpreter)
    # http api endpoint for responses
    input_channel = SimpleWebBot()
    if serve_forever:
        agent.handle_channel(HttpInputChannel(5004, "/", input_channel))
    return agent


if __name__ == '__main__':
    run()
