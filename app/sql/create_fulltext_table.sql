DROP TABLE IF EXISTS item_search;
DROP TRIGGER IF EXISTS trigger_insert_search_item;
DROP TRIGGER IF EXISTS trigger_update_search_item;
DROP TRIGGER IF EXISTS trigger_delete_search_item;

CREATE TABLE item_search 
(
    item_id int primary key,
    search_text TEXT, 
    FULLTEXT (search_text)

) ENGINE="MyISAM";

CREATE TRIGGER trigger_insert_search_item
AFTER INSERT ON Item
FOR EACH ROW
INSERT INTO item_search SET item_id=NEW.id, search_text=CONCAT(NEW.titel,' ',NEW.hoofdtekst,' ',NEW.basistekst,' ',NEW.tweet);


CREATE TRIGGER trigger_update_search_item
AFTER UPDATE ON Item
FOR EACH ROW
UPDATE item_search SET search_text=CONCAT(titel,' ',hoofdtekst,' ',basistekst,' ',tweet) WHERE item_id=OLD.id;

CREATE TRIGGER trigger_delete_search_item
AFTER DELETE ON Item
FOR EACH ROW
DELETE FROM item_search WHERE item_id=OLD.id;

