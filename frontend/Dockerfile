# Этап сборки
FROM node:20-alpine AS build

WORKDIR /app

COPY package*.json ./

# Отключаем строгую проверку SSL для npm внутри контейнера
RUN npm config set strict-ssl false

RUN npm install

COPY . .
RUN npm run build

# Этап продакшена — только статика
FROM nginx:alpine
COPY --from=build /app/dist /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]