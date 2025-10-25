# API REST - Gestion des magasins

Cette API permet de gérer des magasins (CRUD) avec des entrées/sorties JSON. (Développeur·se Back-end PHP | Test technique)

## Installation rapide

Installer docker et docker compose.

1. Cloner le projet
```bash
docker compose up -d
```

## Route Disponible
| Méthode | URI                   | Description               |
|---------|-----------------------|---------------------------|
| POST    | /login                | Authentification          |
| POST    | /stores/create        | Créer un magasin          |
| GET     | /stores               | Lister tous les magasins  |
| GET     | /stores/show?id=XXX   | Récupérer un magasin      |
| PUT     | /stores?id=XXX        | Mettre à jour un magasin  |
| DELETE  | /stores/delete?id=XXX | Supprimer un magasin      |

## Authentification

L'API est sécurisée via un **token JWT**. Pour accéder aux routes protégées (ex : `/stores`), il faut d'abord obtenir un token via la route `/login`.

### Obtenir un token

Effectuez une requête POST vers `/login` avec le corps JSON contenant le `username` et le `password` :

```bash
curl -X POST http://localhost:8080/login \
  -H "Content-Type: application/json" \
  -d '{"username": "test", "password": "test"}'
```

Réponse:
```bash
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlhdCI6MTc2MTM5ODIwNiwiZXhwIjoxNzYxNDAxODA2fQ.EleeZpXT_MglsQ-VDJxXTTbPSlNlmj0oapGkcwHuxJc"
}

```

## Exemple avec curl
1. Créer un magasin
```
curl -X POST http://localhost:8080/stores/create \
     -H "Content-Type: application/json" \
     -H "Authorization: Bearer <votre_token>" \
     -d '{
           "name": "Magasin A",
           "address": "123 Rue Exemple",
           "postalCode": "75001",
           "city": "Paris",
           "country": "France",
           "phoneNumber": "+33123456789"
         }'
```
2. Récupérer la liste des magasin
```
curl -X GET "http://localhost:8080/stores?city=Paris&sort=name&direction=ASC" \
     -H "Authorization: Bearer <votre_token>"
```

3. Récupérer un magasin
```
curl -X GET "http://localhost:8080/stores/show?id=XXX" \
     -H "Authorization: Bearer <votre_token>"
```

4. Modifier un magasin
```
curl -X PUT http://localhost:8080/stores/update?id=XXX \
     -H "Content-Type: application/json" \
     -H "Authorization: Bearer <votre_token>" \
     -d '{
           "name": "Magasin B",
           "address": "124 Rue Exemple",
           "postalCode": "75002",
           "city": "Paris",
           "country": "France",
           "phoneNumber": "+33123456790"
         }'
```

5. Supprimer un magasin
```
curl -X PUT "http://localhost:8080/stores/delete?id=XXX" \
     -H "Authorization: Bearer <votre_token>"
```

## Exemple avec curl
1. Lancer les tests

```
make test
```

1. Lancer les outils de qualité de code

```
make quality
```