-- Hourly Average for last 24 hours
-- NOTE: This is a temporary solution just to demo the data for the dashboard.

create view all_campus_today_avg as

select sum(avg) as all_campus_avg, hour_end from (

-- id, avg count for location by hour, hour it's for
select source_pi_id, avg(device_count), extract(hour from (date_trunc('hour', now()))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '1 hour')  -- start hour
and date_time < (date_trunc('hour', now())) -- end hour
and date(now()) in -- ensure data is for today
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '1 hour')
    and date_time < (date_trunc('hour', now()))
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '1 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '2 hour')
and date_time < (date_trunc('hour', now()) - interval '1 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '2 hour')
    and date_time < (date_trunc('hour', now()) - interval '1 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '2 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '3 hour')
and date_time < (date_trunc('hour', now()) - interval '2 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '3 hour')
    and date_time < (date_trunc('hour', now()) - interval '2 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '3 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '4 hour')
and date_time < (date_trunc('hour', now()) - interval '3 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '4 hour')
    and date_time < (date_trunc('hour', now()) - interval '3 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '4 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '5 hour')
and date_time < (date_trunc('hour', now()) - interval '4 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '5 hour')
    and date_time < (date_trunc('hour', now()) - interval '4 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '5 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '6 hour')
and date_time < (date_trunc('hour', now()) - interval '5 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '6 hour')
    and date_time < (date_trunc('hour', now()) - interval '5 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '6 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '7 hour')
and date_time < (date_trunc('hour', now()) - interval '6 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '7 hour')
    and date_time < (date_trunc('hour', now()) - interval '6 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '7 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '8 hour')
and date_time < (date_trunc('hour', now()) - interval '7 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '8 hour')
    and date_time < (date_trunc('hour', now()) - interval '7 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '8 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '9 hour')
and date_time < (date_trunc('hour', now()) - interval '8 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '9 hour')
    and date_time < (date_trunc('hour', now()) - interval '8 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '9 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '10 hour')
and date_time < (date_trunc('hour', now()) - interval '9 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '10 hour')
    and date_time < (date_trunc('hour', now()) - interval '9 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '10 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '11 hour')
and date_time < (date_trunc('hour', now()) - interval '10 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '11 hour')
    and date_time < (date_trunc('hour', now()) - interval '10 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '11 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '12 hour')
and date_time < (date_trunc('hour', now()) - interval '11 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '12 hour')
    and date_time < (date_trunc('hour', now()) - interval '11 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '12 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '13 hour')
and date_time < (date_trunc('hour', now()) - interval '12 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '13 hour')
    and date_time < (date_trunc('hour', now()) - interval '12 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '13 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '14 hour')
and date_time < (date_trunc('hour', now()) - interval '13 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '14 hour')
    and date_time < (date_trunc('hour', now()) - interval '13 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '14 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '15 hour')
and date_time < (date_trunc('hour', now()) - interval '14 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '15 hour')
    and date_time < (date_trunc('hour', now()) - interval '14 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '15 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '16 hour')
and date_time < (date_trunc('hour', now()) - interval '15 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '16 hour')
    and date_time < (date_trunc('hour', now()) - interval '15 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '16 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '17 hour')
and date_time < (date_trunc('hour', now()) - interval '16 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '17 hour')
    and date_time < (date_trunc('hour', now()) - interval '16 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '17 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '18 hour')
and date_time < (date_trunc('hour', now()) - interval '17 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '18 hour')
    and date_time < (date_trunc('hour', now()) - interval '17 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '18 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '19 hour')
and date_time < (date_trunc('hour', now()) - interval '18 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '19 hour')
    and date_time < (date_trunc('hour', now()) - interval '18 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '19 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '20 hour')
and date_time < (date_trunc('hour', now()) - interval '19 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '20 hour')
    and date_time < (date_trunc('hour', now()) - interval '19 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '20 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '21 hour')
and date_time < (date_trunc('hour', now()) - interval '20 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '21 hour')
    and date_time < (date_trunc('hour', now()) - interval '20 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '21 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '22 hour')
and date_time < (date_trunc('hour', now()) - interval '21 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '22 hour')
    and date_time < (date_trunc('hour', now()) - interval '21 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '22 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '23 hour')
and date_time < (date_trunc('hour', now()) - interval '22 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '23 hour')
    and date_time < (date_trunc('hour', now()) - interval '22 hour')
    )
group by source_pi_id

union

select source_pi_id, avg(device_count), extract(hour from ((date_trunc('hour', now()) - interval '23 hour'))) AS hour_end
from counts
where date_time >= (date_trunc('hour', now()) - interval '24 hour')
and date_time < (date_trunc('hour', now()) - interval '23 hour')
and date(now()) in
    (
    select distinct date(date_time)
    from counts
    where date_time >= (date_trunc('hour', now()) - interval '24 hour')
    and date_time < (date_trunc('hour', now()) - interval '23 hour')
    )
group by source_pi_id

order by hour_end, source_pi_id

) as test
where hour_end <= extract(hour from now())
group by hour_end;

