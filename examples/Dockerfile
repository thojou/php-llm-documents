FROM webdevops/php-dev:8.2

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD true

ENV NODE_VERSION=18.17.0
ENV NVM_DIR=/root/.nvm

RUN apt update && \
    apt install -y curl

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"

RUN npm install -g npm puppeteer


