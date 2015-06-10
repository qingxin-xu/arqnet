/*
 2015 June 09
 Add stripped content attribute to note to store content from the journal entry with HTML tags stripped
 out.
*/
alter table note add column stripped_content mediumtext default null;
update note set stripped_content=content;