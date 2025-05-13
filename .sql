create database farmaceutica

create table trabajadores(
    trabajador_id SERIAL PRIMARY KEY,
    trabajador_nombre VARCHAR(100) NOT NULL,
    trabajador_edad INTEGER,
    trabajador_dpi INTEGER,
    trabajador_puesto VARCHAR(100),
    trabajador_telefono INTEGER,
    trabajador_correo VARCHAR(50),
    trabajador_sueldo INTEGER,
    trabajador_sexo VARCHAR(50),
    trabajador_direccion VARCHAR(250),
    trabajador_situacion SMALLINT DEFAULT 1
);

create table medicamentos(
    medicamento_id SERIAL PRIMARY KEY,
    medicamento_nombre VARCHAR(100) NOT NULL,
    medicamento_vencimiento VARCHAR(25),
    medicamento_descripcion VARCHAR(250),
    medicamento_presentacion VARCHAR(100),
    medicamento_casa_matriz INTEGER,
    medicamento_cantidad INTEGER,
    medicamento_precio DECIMAL(10,2),
    medicamento_situacion SMALLINT DEFAULT 1
);

create table casa_matriz(
    casa_id SERIAL PRIMARY KEY,
    casa_nombre VARCHAR(100) NOT NULL,
    casa_direccion VARCHAR(100),
    casa_telefono INTEGER,
    casa_jefe VARCHAR(50),
    casa_situacion SMALLINT DEFAULT 1
);

alter table medicamentos add constraint (foreign key(medicamento_casa_matriz)
references casa_matriz(casa_id) constraint fk_casa_matriz)

create table clientes(
cliente_id SERIAL PRIMARY KEY,
cliente_nombre VARCHAR(150),
cliente_edad INTEGER,
cliente_dpi INTEGER,
cliente_nit INTEGER,
cliente_telefono INTEGER,
cliente_correo VARCHAR(150),
cliente_sexo VARCHAR(150),
cliente_direccion VARCHAR(150),
cliente_situacion SMALLINT default 1
);

create table ventas(
ventas_id SERIAL PRIMARY KEY,
ventas_medicamento INTEGER,
ventas_cantidad INTEGER,
ventas_cliente INTEGER,
ventas_situacion SMALLINT DEFAULT 1
);

alter table ventas add constraint (foreign key(ventas_medicamento)
references medicamentos(medicamento_id) constraint fk_ventas_medicamento)

alter table ventas add constraint (foreign key(ventas_cantidad)
references medicamentos(medicamento_id) constraint fk_ventas_cantidad)

alter table ventas add constraint (foreign key(ventas_cliente)
references clientes(cliente_id) constraint fk_ventas_clientes)

