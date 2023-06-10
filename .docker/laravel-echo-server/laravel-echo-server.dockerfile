ARG LARAVEL_ECHO_SERVER_NODE_VERSION
FROM $LARAVEL_ECHO_SERVER_NODE_VERSION

WORKDIR /var/www/html/project

RUN mkdir -p /var/ssl

RUN npm install  laravel-echo-server -g
CMD ["laravel-echo-server", "start"]