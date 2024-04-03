FROM nginx:1.19-alpine

RUN rm /etc/nginx/conf.d/default.conf
COPY /docker/configs/nginx/conf.d/local.conf /etc/nginx/nginx.conf

EXPOSE 80

WORKDIR /etc/nginx

CMD ["nginx", "-g", "daemon off;"]
