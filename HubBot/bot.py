from __future__ import absolute_import
from __future__ import division
from __future__ import print_function
from __future__ import unicode_literals

import logging

from rasa_core.agent import Agent
from rasa_core.channels.console import ConsoleInputChannel
from rasa_core.interpreter import RasaNLUInterpreter
from rasa_core.policies.keras_policy import KerasPolicy
from rasa_core.policies.memoization import MemoizationPolicy

logger = logging.getLogger(__name__)


def run_weather_bot(serve_forever=True):
    interpreter = RasaNLUInterpreter('./models/nlu/default/nlu_model')
    agent = Agent.load('./models/dialogue', interpreter=interpreter)

    if serve_forever:
        agent.handle_channel(ConsoleInputChannel())

    return agent


if __name__ == '__main__':
    run_weather_bot()
