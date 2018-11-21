CREATE TABLE recurring_booking (
	term_name VARCHAR(255) NOT NULL,
        /* Weeks = get_weeks(start_date, end_date) */
	start_date VARCHAR(255) NOT NULL,
	end_date VARCHAR(255) NOT NULL,
);
