/* Make a booking to occur every 'jump' days */
CREATE TABLE recurring_booking (
	category VARCHAR(255) NOT NULL,
	r_id VARCHAR(255) NOT NULL,
	column_name VARCHAR(255) NOT NULL,
	username VARCHAR(255) NOT NULL,

	start_date VARCHAR(255) NOT NULL, /* stomps with current date('y-m-d') */
	end_date VARCHAR(255) NOT NULL, /* stomps with current date('y-m-d') */
	jump VARCHAR(255) NOT NULL /* jump = "1 week" || "2 week" || "1 month" etc... */
);
