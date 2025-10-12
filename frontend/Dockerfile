FROM node:18-alpine

# Vue CLI のインストール
RUN npm install -g @vue/cli

# 作業ディレクトリの設定
WORKDIR /app

# package.json のコピーと依存関係のインストール
COPY package*.json ./
RUN npm install

# ソースコードのコピー
COPY . .

EXPOSE 8080
CMD ["npm", "run", "serve"]
