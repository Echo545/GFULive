create table pi (
    pi_id int primary key,
    pi_location text
);

create table pi_ip (
    source_pi_id int primary key,
    ip varchar(60),
    foreign key (source_pi_id) references pi (pi_id)
);

create table counts (
    count_id int primary key,
    source_pi_id int,
    device_count int not null,
    date_time timestamp not null,
    foreign key (source_pi_id) references pi (pi_id)
);


create view avg_counts as
select source_pi_id, avg(device_count)
from counts group by source_pi_id;

