FROM node:20-alpine

WORKDIR /app

COPY package*.json ./

# Отключаем строгую проверку SSL для npm внутри контейнера
RUN npm config set strict-ssl false

# Теперь установка пройдет без ошибок
RUN npm install

COPY . .

EXPOSE 5000

CMD ["npm", "start"]