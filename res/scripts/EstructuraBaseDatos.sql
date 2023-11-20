-- ------------------------------------------------------------------------
-- SCRIPT PARA CREAR LA BASE DE DATOS
-- ------------------------------------------------------------------------



-- SECUECIAS
-- ------------------------------------------------------------------------

CREATE SEQUENCE usuario_id_seq;
CREATE SEQUENCE caracteristica_id_seq;
CREATE SEQUENCE guion_id_seq;
CREATE SEQUENCE instruccion_id_seq;
CREATE SEQUENCE nodo_id_seq;
CREATE SEQUENCE palabra_clave_id_seq;
CREATE SEQUENCE parrafo_id_seq;
CREATE SEQUENCE personaje_id_seq;
CREATE SEQUENCE relacion_id_seq;
CREATE SEQUENCE relato_id_seq;




-- TABLAS
-- ------------------------------------------------------------------------

-- Table: public.usuario
CREATE TABLE public.usuario
(
  id integer NOT NULL DEFAULT nextval('usuario_id_seq'::regclass),
  login character varying(16),
  password character varying(64)
);

-- Table: public.caracteristica
CREATE TABLE public.caracteristica
(
  id integer NOT NULL DEFAULT nextval('caracteristica_id_seq'::regclass),
  nombre character varying(32)
);

-- Table: public.guion
CREATE TABLE public.guion
(
  id integer NOT NULL DEFAULT nextval('guion_id_seq'::regclass),
  id_parrafo_ini integer,
  titulo character varying(32),
  profundidad smallint,
  refrescada boolean
);

-- Table: public.instruccion
CREATE TABLE public.instruccion
(
  id integer NOT NULL DEFAULT nextval('instruccion_id_seq'::regclass),
  operacion character varying(64),
  descripcion character varying(256)
);

-- Table: public.nodo
CREATE TABLE public.nodo
(
  id integer NOT NULL DEFAULT nextval('nodo_id_seq'::regclass),
  id_relato integer,
  id_nodo_padre integer,
  id_nodo_hijo integer,
  texto character varying(512),
  orden integer
);

-- Table: public.nodo_personaje
CREATE TABLE public.nodo_personaje
(
  id_nodo integer NOT NULL,
  id_personaje integer NOT NULL,
  presente boolean
);

-- Table: public.palabra_clave
CREATE TABLE public.palabra_clave
(
  id integer NOT NULL DEFAULT nextval('palabra_clave_id_seq'::regclass),
  nombre character varying(32)
);

-- Table: public.palabra_clave_valor
CREATE TABLE public.palabra_clave_valor
(
  id_palabra_clave integer NOT NULL,
  valor character varying(32) NOT NULL,
  nivel smallint
);

-- Table: public.parrafo
CREATE TABLE public.parrafo
(
  id integer NOT NULL DEFAULT nextval('parrafo_id_seq'::regclass),
  id_guion integer,
  nivel smallint,
  operaciones character varying(256),
  texto character varying(512),
  marcado boolean DEFAULT false,
  profundidad integer DEFAULT 0
);

-- Table: public.parrafo_hijos
CREATE TABLE public.parrafo_hijos
(
  id_parrafo_padre integer NOT NULL,
  id_parrafo_hijo integer NOT NULL,
  id_guion integer
);

-- Table: public.personaje
CREATE TABLE public.personaje
(
  id integer NOT NULL DEFAULT nextval('personaje_id_seq'::regclass),
  nombre character varying(16),
  nombre_largo character varying(32),
  sexo boolean,
  anyo date,
  numero_imagen smallint
);

-- Table: public.personaje_caracteristica
CREATE TABLE public.personaje_caracteristica
(
  id_personaje integer NOT NULL,
  id_caracteristica integer NOT NULL,
  nivel smallint
);

-- Table: public.personaje_imagen
CREATE TABLE public.personaje_imagen
(
  id_personaje integer NOT NULL,
  nombre_imagen character varying(16) NOT NULL
);

-- Table: public.personaje_relacion
CREATE TABLE public.personaje_relacion
(
  id_personaje1 integer NOT NULL,
  id_personaje2 integer NOT NULL,
  id_relacion integer NOT NULL
);

-- Table: public.relacion
CREATE TABLE public.relacion
(
  id integer NOT NULL DEFAULT nextval('relacion_id_seq'::regclass),
  nombre character varying(32)
);

-- Table: public.relato
CREATE TABLE public.relato
(
  id integer NOT NULL DEFAULT nextval('relato_id_seq'::regclass),
  id_guion integer,
  id_nodo_ini integer,
  titulo character varying(256),
  generado integer DEFAULT 0,
  cantidad_nodos integer
);

-- Table: public.relato_personaje
CREATE TABLE public.relato_personaje
(
  id_relato integer NOT NULL,
  id_personaje integer NOT NULL,
  etiqueta_personaje character varying(8)
);



-- CLAVES PRIMARIAS
-- ------------------------------------------------------------------------

ALTER TABLE usuario ADD primary key (id);
ALTER TABLE caracteristica ADD primary key (id);
ALTER TABLE guion ADD primary key (id);
ALTER TABLE instruccion ADD primary key (id);
ALTER TABLE nodo ADD primary key (id);
ALTER TABLE nodo_personaje ADD primary key (id_nodo, id_personaje);
ALTER TABLE palabra_clave ADD primary key (id);
ALTER TABLE palabra_clave_valor ADD primary key (id_palabra_clave, valor);
ALTER TABLE parrafo ADD primary key (id);
ALTER TABLE parrafo_hijos ADD primary key (id_parrafo_padre, id_parrafo_hijo);
ALTER TABLE personaje ADD primary key (id);
ALTER TABLE personaje_caracteristica ADD primary key (id_personaje, id_caracteristica);
ALTER TABLE personaje_imagen ADD primary key (id_personaje, nombre_imagen);
ALTER TABLE personaje_relacion ADD primary key (id_personaje1, id_personaje2, id_relacion);
ALTER TABLE relacion ADD primary key (id);
ALTER TABLE relato ADD primary key (id);
ALTER TABLE relato_personaje ADD primary key (id_relato, id_personaje);



-- CLAVES AGENAS
-- ------------------------------------------------------------------------

-- Clave agena guion.id_parrafo_ini
ALTER TABLE guion 
ADD CONSTRAINT guion_id_parrafo_ini_fkey
FOREIGN KEY (id_parrafo_ini) 
REFERENCES public.parrafo (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE NO ACTION; 

-- Clave agena nodo.id_nodo_hijo
ALTER TABLE nodo 
ADD CONSTRAINT nodo_id_nodo_hijo_fkey
FOREIGN KEY (id_nodo_hijo) 
REFERENCES public.nodo (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE NO ACTION; 

-- Clave agena nodo.id_nodo_padre
ALTER TABLE nodo 
ADD CONSTRAINT nodo_id_nodo_padre_fkey
FOREIGN KEY (id_nodo_padre) 
REFERENCES public.nodo (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE NO ACTION; 

-- Clave agena nodo.id_relato
ALTER TABLE nodo 
ADD CONSTRAINT nodo_id_relato_fkey
FOREIGN KEY (id_relato) 
REFERENCES public.relato (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena nodo_personaje.id_nodo
ALTER TABLE nodo_personaje 
ADD CONSTRAINT nodo_personaje_id_nodo_fkey
FOREIGN KEY (id_nodo) 
REFERENCES public.nodo (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena nodo_personaje.id_personaje
ALTER TABLE nodo_personaje 
ADD CONSTRAINT nodo_personaje_id_personaje_fkey
FOREIGN KEY (id_personaje) 
REFERENCES public.personaje (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena: palabra_clave_valor.id_palabra_clave
ALTER TABLE palabra_clave_valor 
ADD CONSTRAINT palabra_clave_valor_id_palabra_clave_fkey
FOREIGN KEY (id_palabra_clave) 
REFERENCES public.palabra_clave (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena: parrafo.id_guion
ALTER TABLE parrafo 
ADD CONSTRAINT parrafo_id_guion_fkey
FOREIGN KEY (id_guion) 
REFERENCES public.guion (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena parrafo_hijos.parrafo_hijos_id_guion_fkey
ALTER TABLE parrafo_hijos 
ADD CONSTRAINT parrafo_hijos_id_guion_fkey
FOREIGN KEY (id_guion) 
REFERENCES public.guion (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE NO ACTION; 

-- Clave agena: parrafo_hijos.id_parrafo_hijo
ALTER TABLE parrafo_hijos 
ADD CONSTRAINT parrafo_hijos_id_parrafo_hijo_fkey
FOREIGN KEY (id_parrafo_hijo) 
REFERENCES public.parrafo (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena parrafo_hijos.id_parrafo_padre
ALTER TABLE parrafo_hijos 
ADD CONSTRAINT parrafo_hijos_id_parrafo_padre_fkey
FOREIGN KEY (id_parrafo_padre) 
REFERENCES public.parrafo (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena: personaje_caracteristica.id_caracteristica
ALTER TABLE personaje_caracteristica 
ADD CONSTRAINT personaje_caracteristica_id_caracteristica_fkey
FOREIGN KEY (id_caracteristica) 
REFERENCES public.caracteristica (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena personaje_caracteristica.id_personaje
ALTER TABLE personaje_caracteristica 
ADD CONSTRAINT personaje_caracteristica_id_personaje_fkey
FOREIGN KEY (id_personaje) 
REFERENCES public.personaje (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena personaje_imagen.id_personaje
ALTER TABLE personaje_imagen 
ADD CONSTRAINT personaje_imagen_id_personaje_fkey
FOREIGN KEY (id_personaje) 
REFERENCES public.personaje (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena: personaje_relacion.id_personaje1
ALTER TABLE personaje_relacion 
ADD CONSTRAINT personaje_relacion_id_personaje1_fkey
FOREIGN KEY (id_personaje1) 
REFERENCES public.personaje (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena: personaje_relacion.id_personaje2
ALTER TABLE personaje_relacion 
ADD CONSTRAINT personaje_relacion_id_personaje2_fkey
FOREIGN KEY (id_personaje2) 
REFERENCES public.personaje (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena: personaje_relacion.id_relacion
ALTER TABLE personaje_relacion 
ADD CONSTRAINT personaje_relacion_id_relacion_fkey
FOREIGN KEY (id_relacion) 
REFERENCES public.relacion (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena relato.id_guion
ALTER TABLE relato 
ADD CONSTRAINT relato_id_guion_fkey
FOREIGN KEY (id_guion) 
REFERENCES public.guion (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE NO ACTION; 

-- Clave agena relato_personaje.id_personaje
ALTER TABLE relato_personaje 
ADD CONSTRAINT relato_personaje_id_personaje_fkey
FOREIGN KEY (id_personaje) 
REFERENCES public.personaje (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 

-- Clave agena relato_personaje.id_relato
ALTER TABLE relato_personaje 
ADD CONSTRAINT relato_personaje_id_relato_fkey
FOREIGN KEY (id_relato) 
REFERENCES public.relato (id) MATCH SIMPLE
ON UPDATE NO ACTION ON DELETE CASCADE; 



-- VISTAS
-- ------------------------------------------------------------------------

-- Vista vista_relatos_guiones
CREATE OR REPLACE VIEW public.vista_relatos_guiones AS 
 SELECT r.id,
    r.id_guion,
    r.id_nodo_ini,
    r.titulo,
    r.generado,
    r.cantidad_nodos,
    g.id_parrafo_ini,
    g.titulo AS titulo_guion,
    g.profundidad,
    g.refrescada
   FROM relato r
     LEFT JOIN guion g ON r.id_guion = g.id;

-- Vista vista_personaje_caracteristicas
CREATE OR REPLACE VIEW public.vista_personaje_caracteristicas AS 
 SELECT pc.id_personaje,
    pc.id_caracteristica,
    pc.nivel,
    c.nombre AS nombre_caracteristica,
    p.sexo
   FROM personaje_caracteristica pc
     LEFT JOIN caracteristica c ON c.id = pc.id_caracteristica
     LEFT JOIN personaje p ON p.id = pc.id_personaje;

-- Vista vista_personaje_relaciones
CREATE OR REPLACE VIEW public.vista_personaje_relaciones AS 
 SELECT pr.id_personaje1,
    pr.id_personaje2,
    pr.id_relacion,
    p1.sexo as sexo1,
    p2.nombre,
    p2.nombre_largo,
    p2.sexo as sexo2,
    p2.anyo,
    p2.numero_imagen,
    r.nombre AS nombre_relacion
   FROM personaje_relacion pr
     LEFT JOIN personaje p1 ON p1.id = pr.id_personaje1
     LEFT JOIN personaje p2 ON p2.id = pr.id_personaje2
     LEFT JOIN relacion r ON r.id = pr.id_relacion;

-- Vista vista_parrafos_hijo
 CREATE OR REPLACE VIEW public.vista_parrafos_hijo AS 
select 
ph.id_parrafo_padre,
ph.id_parrafo_hijo,
p.id_guion,
p.nivel,
p.operaciones,
p.texto,
p.marcado,
p.profundidad
from parrafo_hijos ph left join parrafo p on p.id = ph.id_parrafo_hijo;

-- Vista vista_parrafos_padre
CREATE OR REPLACE VIEW public.vista_parrafos_padre AS 
select 
ph.id_parrafo_hijo,
ph.id_parrafo_padre,
p.id_guion,
p.nivel,
p.operaciones,
p.texto,
p.marcado,
p.profundidad
from parrafo_hijos ph left join parrafo p on p.id = ph.id_parrafo_padre;

-- Vista vista_nodo_personajes
CREATE OR REPLACE VIEW public.vista_nodo_personajes AS 
 SELECT DISTINCT ON (p.nombre_largo, np.id_personaje) np.id_nodo,
    np.id_personaje,
    p.nombre,
    p.nombre_largo,
    p.sexo,
    p.anyo,
    p.numero_imagen,
    np.presente,
    pi.nombre_imagen
   FROM nodo_personaje np
     LEFT JOIN personaje p ON np.id_personaje = p.id
     LEFT JOIN personaje_imagen pi ON pi.id_personaje = p.id
  ORDER BY p.nombre_largo, np.id_personaje, (random());

-- Vista vista_relato_personajes
CREATE OR REPLACE VIEW public.vista_relato_personajes AS 
select * from relato_personaje rp left join personaje p on rp.id_personaje = p.id;

