DROP TABLE IF EXISTS item_search;
DROP TRIGGER IF EXISTS trigger_insert_search_item;
DROP TRIGGER IF EXISTS trigger_update_search_item;
DROP TRIGGER IF EXISTS trigger_delete_search_item;
DROP PROCEDURE IF EXISTS index_item_search;


CREATE TABLE item_search 
(
    item_id int primary key,
    search_text TEXT, 
    keywords TEXT,
    FULLTEXT (keywords),
    FULLTEXT (search_text)

) ENGINE="MyISAM";

CREATE TRIGGER trigger_insert_search_item
AFTER INSERT ON Item
FOR EACH ROW
INSERT INTO item_search SET item_id=NEW.id, keywords=NEW.zoek_trefwoorden, search_text=NEW.zoek_tekst;


CREATE TRIGGER trigger_update_search_item
AFTER UPDATE ON Item
FOR EACH ROW
UPDATE item_search SET keywords=NEW.zoek_trefwoorden, search_text=NEW.zoek_tekst WHERE item_id=OLD.id;

CREATE TRIGGER trigger_delete_search_item
AFTER DELETE ON Item
FOR EACH ROW
DELETE FROM item_search WHERE item_id=OLD.id;


DELETE FROM item_search;
INSERT INTO item_search (item_id,keywords,search_text) SELECT id, zoek_trefwoorden,zoek_tekst FROM Item;