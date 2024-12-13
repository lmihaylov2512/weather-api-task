FROM node:22.12.0-alpine

# set working directory
WORKDIR /var/www/app

# copy a custom start script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# change non-root user for security concern
USER node

# expose the Vite port and start the custom script
EXPOSE 5173
CMD ["sh", "/usr/local/bin/start.sh"]
