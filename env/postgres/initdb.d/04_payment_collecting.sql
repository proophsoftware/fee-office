CREATE SCHEMA payment;

CREATE TABLE payment.event_streams (
  no BIGSERIAL,
  real_stream_name VARCHAR(150) NOT NULL,
  stream_name CHAR(41) NOT NULL,
  metadata JSONB,
  category VARCHAR(150),
  PRIMARY KEY (no),
  UNIQUE (stream_name)
);

INSERT INTO payment.event_streams (real_stream_name, stream_name, metadata) VALUES ('event_stream', '_4228e4a00331b5d5e751db0481828e22a2c3c8ef', '[]');

CREATE TABLE payment.projections (
  no BIGSERIAL,
  name VARCHAR(150) NOT NULL,
  position JSONB,
  state JSONB,
  status VARCHAR(28) NOT NULL,
  locked_until CHAR(26),
  PRIMARY KEY (no),
  UNIQUE (name)
);

CREATE INDEX on payment.event_streams (category);

create table if not exists payment._4228e4a00331b5d5e751db0481828e22a2c3c8ef
(
	no bigserial not null
		constraint _4228e4a00331b5d5e751db0481828e22a2c3c8ef_pkey
			primary key,
	event_id uuid not null
		constraint _4228e4a00331b5d5e751db0481828e22a2c3c8ef_event_id_key
			unique,
	event_name varchar(100) not null,
	payload json not null,
	metadata jsonb not null
		constraint aggregate_id_not_null
			check ((metadata ->> '_aggregate_id'::text) IS NOT NULL)
		constraint aggregate_type_not_null
			check ((metadata ->> '_aggregate_type'::text) IS NOT NULL)
		constraint aggregate_version_not_null
			check ((metadata ->> '_aggregate_version'::text) IS NOT NULL),
	created_at timestamp(6) not null
)
;

create unique index if not exists _4228e4a00331b5d5e751db0481828e22a2c3c8ef_expr_expr1_expr2_idx
	on payment._4228e4a00331b5d5e751db0481828e22a2c3c8ef ((metadata ->> '_aggregate_type'::text), (metadata ->> '_aggregate_id'::text), (metadata ->> '_aggregate_version'::text))
;

create index if not exists _4228e4a00331b5d5e751db0481828e22a2c3c8ef_expr_expr1_no_idx
	on payment._4228e4a00331b5d5e751db0481828e22a2c3c8ef ((metadata ->> '_aggregate_type'::text), (metadata ->> '_aggregate_id'::text), no)
;

CREATE USER payment WITH PASSWORD 'changeme';

ALTER USER payment SET search_path TO payment;

GRANT ALL PRIVILEGES ON SCHEMA payment to payment;

GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA payment to payment;

GRANT ALL PRIVILEGES ON SEQUENCE payment._4228e4a00331b5d5e751db0481828e22a2c3c8ef_no_seq TO payment;

GRANT ALL PRIVILEGES ON SEQUENCE payment.projections_no_seq TO payment;