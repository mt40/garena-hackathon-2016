// champions in the same match
with champion_pairs as (
select  --a1.garena_uid as uid1, 
        a1.champion_item_id as champion_id1,
        --a2.garena_uid as uid2, 
        a2.champion_item_id as champion_id2
from lol.fact_gameplay a1 join lol.fact_gameplay a2 on a1.battle_id = a2.battle_id 
--where a1.battle_id = 1659453094
  and a1.garena_uid < a2.garena_uid
--limit 1000
)
select champion_id1, champion_id2, count(*)
from champion_pairs
group by champion_id1, champion_id2

// champion informations

with uid_list as (
    select uid from lol.lol_user_profile
    where grass_region = 'sg' and grass_date = date_add(current_date(), -1)
      and recent7d_game_count > 0
    order by rand()
    limit 1000
)
select garena_uid, champion_item_id, count(*) as usage
from lol.fact_gameplay
where raw_game_type = 'MATCHED_GAME'
group by 1, 2
limit 1000

// champion popularity
select champion_item_id, count(distinct garena_uid) as user_count
from lol.fact_gameplay
where raw_game_type = 'MATCHED_GAME'
group by champion_item_id
