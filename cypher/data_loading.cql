LOAD CSV WITH HEADERS FROM "file:///" AS row
CREATE (r:Relation)
Set r = row
;

MATCH (r:Relation) RETURN r LIMIT 20;

