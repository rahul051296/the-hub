def add_data(nlu_dir, value):
        text = value.get('text')
        entities = value.get('entities')
        text_split = text.split()
        for entity in entities:
            text_split = [txt.replace(entity.get('entity_value'), "[{}]({})".format(entity.get('entity_value'), entity.get('entity'))) for txt in text_split]
        value = " ".join(text_split)
        with open(nlu_dir, "a+") as data:
            if data.write("\n- {}".format(value)):
                print("Successfully added {} into {}".format(value, data.name))
            else:
                print("Failed to add {} into {}".format(value, data.name))


def intent_status(message, intent):
    add_data(("./data/nlu/" + intent + ".md"), message)


if __name__ == '__main__':
    intent_status({'text': 'what about coimbatore', 'entities': [dict(entity='location', entity_value='coimbatore')]}, "weather_details")
