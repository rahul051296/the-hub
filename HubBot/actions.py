from __future__ import absolute_import
from __future__ import division
from __future__ import unicode_literals

from rasa_core.actions.action import Action
from rasa_core.events import SlotSet


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
