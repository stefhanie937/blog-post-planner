FROM hhvm/hhvm:3.30-latest

RUN apt-get update && apt-get install -y \
    nginx curl bash \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/blog_planner
COPY . .

COPY nginx.conf /etc/nginx/sites-available/default
COPY start.sh /start.sh
RUN chmod +x /start.sh
RUN mkdir -p /var/run/nginx /tmp

EXPOSE 80
CMD ["/start.sh"]