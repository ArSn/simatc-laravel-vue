FROM nginx:stable-alpine

COPY nginx.conf /etc/nginx/nginx.conf
COPY includes/mime.types /etc/nginx/mime.types
COPY includes/fpm-pass.conf /etc/nginx/fpm-pass.conf
COPY includes/ws-proxy-support.conf /etc/nginx/ws-proxy-support.conf
COPY includes/proxy.conf /etc/nginx/proxy.conf
RUN rm /etc/nginx/conf.d/default.conf