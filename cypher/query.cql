MATCH p=(u: GarenaUID{id: 283790038})-[]-()-[s:SAME_MATCH]-(c: Champion)
WHERE s.compatibility > 0
RETURN c.champion_name as food_name, s.compatibility as strength
ORDER BY strength DESC
LIMIT 10
