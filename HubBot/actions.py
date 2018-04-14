from __future__ import absolute_import
from __future__ import division
from __future__ import unicode_literals

from rasa_core.actions.action import Action
from rasa_core.events import SlotSet
from soup import search_word_meaning, search_weather_details, translate_word


class ActionSearch(Action):

    def name(self):
        return 'utter_info'

    def run(self, dispatcher, tracker, domain):
        query = str(tracker.get_slot('query'))
        response = "I don't understand."
        if query == 'interests' or query == 'interest':
            response = "You can add your interests by clicking <a href='http://localhost/the-hub/interests.php' class='link' target='_blank'>here.</a> We will currate your news feed based on these interests."
        elif query == 'users' or query == 'user':
            response = "Other users of <b>The Hub</b> can be found <a href='http://localhost/the-hub/users.php' class='link' target='_blank'>here.</a> We will update your news feed when a user posts something."
        dispatcher.utter_message(response)
        return [SlotSet('query', None)]


class SearchUser(Action):

    def name(self):
        return 'utter_search'

    def run(self, dispatcher, tracker, domain):
        user = str(tracker.get_slot('user'))
        if user == "None":
            response = "Sorry, I couldn't understand your question."
        else:
            response = "I searched for <b><span style='text-transform:capitalize'>{}</span></b> and <a href='http://localhost/the-hub/search.php?query={}&search=' class='link' target='_blank'>this </a>is what came back.<br> ".format(
                user, user)
        dispatcher.utter_message(response)
        return [SlotSet('user', None)]

class GetWeatherDetails(Action):
    def name(self):
        return 'utter_weather_details'

    def run(self, dispatcher, tracker, domain):
        location = str(tracker.get_slot('location'))
        if location == 'None':
            dispatcher.utter_message('You will have to provide the location for me to get you the weather details  ')
        else:
            message = search_weather_details(location)
            dispatcher.utter_message(message)
        return [SlotSet('location', None)]


class GetWordMeaning(Action):
    def name(self):
        return 'utter_word_meaning'

    def run(self, dispatcher, tracker, domain):
        word = str(tracker.get_slot('meaning'))
        if word == 'None':
            dispatcher.utter_message('What meaning would you like to know?')
        else:
            message = search_word_meaning(word)
            dispatcher.utter_message(message)
        return [SlotSet('meaning', None)]


class GetTranslation(Action):
    def name(self):
        return 'utter_translate_data'

    def run(self, dispatcher, tracker, domain):
        word = str(tracker.get_slot('word'))
        language = str(tracker.get_slot('language'))
        if (word == 'None') or (language == 'None'):
            dispatcher.utter_message("You have to provide the word and language in order for me to translate")
        else:
            message = translate_word(word, language)
            dispatcher.utter_message(message)
            return [SlotSet('language', None)]