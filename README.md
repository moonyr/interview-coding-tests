# API REST - Gestion des magasins

Cette API permet de gérer des magasins (CRUD) avec des entrées/sorties JSON.

## Installation rapide

1. Cloner le projet
```bash
git clone <repo-url>
cd <repo>
```

## Route Disponible
| Méthode | URI                   | Description               |
|---------|-----------------------|---------------------------|
| POST    | /stores/create        | Créer un magasin          |
| GET     | /stores               | Lister tous les magasins  |
| GET     | /stores/show?id=XXX   | Récupérer un magasin      |
| PUT     | /stores?id=XXX        | Mettre à jour un magasin  |
| DELETE  | /stores/delete?id=XXX | Supprimer un magasin      |

## Exemple avec curl
1. Créer un magasin
```
curl -X POST http://localhost:8080/stores/create \
     -H "Content-Type: application/json" \
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
curl -X GET "http://localhost:8080/stores?city=Paris&sort=name&direction=ASC"
```

3. Récupérer un magasin
```
curl -X GET "http://localhost:8080/stores/show?id=XXX"
```

4. Modifier un magasin
```
curl -X PUT http://localhost:8080/stores/update?id=XXX \
     -H "Content-Type: application/json" \
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
curl -X PUT "http://localhost:8080/stores/delete?id=XXX"
```