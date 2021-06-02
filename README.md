Php app demo using Nested set model. (see https://en.wikipedia.org/wiki/Nested_set_model​ for further details).

This repo is made to be used with Docker Compose.
Docker settings can be change inside docker-compose.yml file.

Steps usage:
 - Download or clone https://github.com/markirnanflores/nested-set-model-demo.git
 - Run "docker compose up"
 - After the installation process go to http://localhost and see if the app is working. (It should show a json string with some errors)

Url samples for testing:
 - http://localhost?node_id=5&language=italian
 - http://localhost?node_id=5&language=italian&page_size=5&page_num=0
 - http://localhost?node_id=7&language=italian&search_keyword=eur
