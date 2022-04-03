# NEBot

Docker crated image and build

- docker image build -t nexpbot .

Docker start Service API

- docker run -d --name nexpbotapi -p 8000:8000 nexpbotapi

Docker start service consumer Data MongoDB

- docker run -d --name nexpbotconsumer nexpbotconsumer

Docker start service worker to write System BOT

- docker run -d --name nexpbotworker nexpbotworker

Docker start service command to write System BOT

- docker run -d --name nexpbotcommand -e COMMAND_SERVER_ID="1" nexpbotcommand
