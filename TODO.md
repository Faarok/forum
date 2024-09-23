# 👨🏼‍💻 Framework - Forum

*Par Svein SAMSON*

> Légende :
* ✅ : Fait
* ⚠️ : Interrompu / En cours
* ❌ : Annulé

## <span style="color: red;">Back-end</span>
### <span style="color: green;">Router</span>
* ⚠️ Création complète du Router :
    * ✅ `get` ;
    * `post` ;

### <span style="color: green;">Entity</span>
* ✅ Création de la class et mise en place du `__construct`, de la création de table, de la connexion à la BDD, et la structure globale de la class et de ses enfants ;
* ✅ Création du CRUD ;
* ⚠️ Création de méthodes supplémentaires :
    * `hardDelete` → Suppression complète de la donnée ;
    * `save` → Si la donnée possède un ID alors `update`, sinon `create` ;
    * `select` ;
    * `where` ;
    * `getBydId` qui utilise `select` ;
    * `load` retourne un résultat unique (`fetch`) pour une requête demandée ;
    * `loadAll` retourne tous les resultats (`fetchAll`) pour une requête demandée ;

### <span style="color: green;">Migration</span>

### <span style=="color: green;">User</span>
* CRUD :
    * `createUser` ;
    * `readUser` ;
    * `updateUser` ;
    * `deleteUser` ;