// read csv file from local
LOAD CSV WITH HEADERS FROM "file:///champion_pairs.csv" AS row
CREATE (r:Relation)
Set r = row
;

MATCH (r:Relation) RETURN r LIMIT 20;


// extract champions as node
MATCH (r: Relation)
WITH DISTINCT r.champion_id1 as champion
CREATE (c: Champion)
SET c.champion_id = champion
;

MATCH (r: Relation)
WITH DISTINCT r.champion_id2 as champion
CREATE (c: Champion)
SET c.champion_id = champion
;

CREATE INDEX ON :Champion(champion_id);


MATCH (r: Relation)
WITH r
MATCH (c: Champion)
WHERE r.champion_id1 = c.champion_id
CREATE (c)-[:CHAMPION]->(r)
;

MATCH (r: Relation)
WITH r
MATCH (c: Champion)
WHERE r.champion_id2 = c.champion_id
CREATE (c)-[:CHAMPION]->(r)
;

MATCH (r: Relation)
SET r.intensity = r.`count(1)`
;

MATCH (c1)-[:CHAMPION]->(r)<-[:CHAMPION]-(c2)
CREATE (c1)-[:SAME_MATCH{intensity: r.intensity}]->(c2)
;

MATCH (r: Relation)
DETACH DELETE r
;

// load champion attributes
LOAD CSV WITH HEADERS FROM "file:///champions.csv" AS row
CREATE (c:Champions)
Set c = row
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.champion_name = c2.skin_name
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.bot_mm_enabled = c2.bot_mm_enabled
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.bot_spell_2_OBS_20110215 = c2.bot_spell_2_OBS_20110215
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.magic_rank = c2.magic_rank
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.ranked_play_enabled = c2.ranked_play_enabled
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.active = c2.active
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.bot_enabled = c2.bot_enabled
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.difficulty_rank = c2.difficulty_rank
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.attack_rank = c2.attack_rank
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.free_to_play = c2.free_to_play
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.defense_rank = c2.defense_rank
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.ability_icon_1 = c2.ability_icon_1
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.ability_icon_2 = c2.ability_icon_2
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.ability_icon_3 = c2.ability_icon_3
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.ability_icon_4 = c2.ability_icon_4
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.bot_spell_1_OBS_20110215 = c2.bot_spell_1_OBS_20110215
;

MATCH (c: Champions)
DETACH DELETE c
;

// load champion roles
LOAD CSV WITH HEADERS FROM "file:///champion_tag.csv" AS row
CREATE (c:Champions)
Set c = row
;

MATCH (c1: Champion)
WITH c1
MATCH (c2: Champions)
WHERE c1.champion_id = c2.champion_id
SET c1.tag = c2.tag
;

MATCH (c: Champions)
DETACH DELETE c
;

MATCH ()-[r:SAME_MATCH]-()
SET r.strength = toInt(r.intensity)
;

MATCH p=()-[r:SAME_MATCH]-()
WHERE r.strength > 1000000
RETURN p
;

// load uid gameplay history
LOAD CSV WITH HEADERS FROM "file:///uid_champion_usage.csv" AS row
CREATE (r:Relation)
Set r = row
;

MATCH (r: Relation)
CREATE (u: GarenaUID)
SET u.uid = r.garena_uid
;

MATCH (r: Relation)
WITH r
MATCH (u: GarenaUID)
WHERE u.uid = r.garena_uid
CREATE (u)-[:UID]->(r)
;

MATCH (r: Relation)
WITH r
MATCH (c: Champion)
WHERE r.champion_item_id = c.champion_id
CREATE (c)-[:PLAYED]->(r)
;

MATCH (r: Relation)
WITH r
MATCH (c: Champion)-[]-(r)-[]-(u: GarenaUID)
CREATE (c)-[:PLAYED_BY{usage: r.usage}]->(u)
;

MATCH (r: Relation)
DETACH DELETE r
;

// load uid gameplay history
LOAD CSV WITH HEADERS FROM "file:///champion_popularity.csv" AS row
CREATE (r:Relation)
Set r = row
;

MATCH (r: Relation)
WITH r
MATCH (c: Champion)
WHERE r.champion_item_id = c.champion_id
SET c.popularity = r.`count(1)`
;

LOAD CSV WITH HEADERS FROM "file:///champion_users.csv" AS row
CREATE (r:Relation)
Set r = row
;

MATCH (r: Relation)
WITH r
MATCH (c: Champion)
WHERE r.champion_item_id = c.champion_id
SET c.users = r.user_count
;

MATCH (r: Relation)
DETACH DELETE r
;

MATCH (n:GarenaUID)
SET n.id = toInt(n.uid)
;

CREATE INDEX ON :GarenaUID(id);
CREATE INDEX ON :Champion(tag);
CREATE INDEX ON :Champion(champion_name);
CREATE INDEX ON :Champion(user_count);
CREATE INDEX ON :Champion(usage);

MATCH (u: GarenaUID)
REMOVE u.uid
;
