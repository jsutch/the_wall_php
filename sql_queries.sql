-- Login/Registration
-- create a user
INSERT INTO users (email, first_name, last_name, password, created_at, updated_at) 
VALUES (:email, :first_name, :last_name, :pw_hash, NOW(), NOW());

-- login a user

SELECT * FROM users WHERE email = :email LIMIT 1


-- The Wall
--get messages to display
SELECT m.id, concat(u.first_name, ' ', u.last_name) as name, m.message, date_format(m.updated_at, '%M %D %Y'), m.updated_at as mdate FROM users u, messages m WHERE u.id = m.user_id ORDER BY m.updated_at DESC;


--  get comments for messages
SELECT m.id as mid, concat(u.first_name, ' ', u.last_name) as name, c.comment,m.id as cmid, c.id as cid, date_format(m.updated_at, '%M %D %Y at %H:%m:%s') as mupdate, date_format(c.updated_at, '%M %D %Y at %H:%m:%s') as cupdate FROM users u, comments c, messages mWHERE u.id = c.user_id and c.message_id = m.id ORDER BY m.updated_at DESC;

--add messages

insert into messages (id, user_id, message, created_at, updated_at) values (:id, :user_id, :message, NOW(), NOW())
insert into messages (user_id, message, created_at, updated_at)
VALUES (1, 'I am the first message', NOW(), NOW());

--add comments for a message

insert into comments (user_id, comment, message_id, created_at, updated_at ) VALUES (:user_id, :comment, message_id, NOW(), NOW()))
