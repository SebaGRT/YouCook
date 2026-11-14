const u = (id: string) => `https://images.unsplash.com/${id}?crop=entropy&cs=tinysrgb&fit=crop&fm=jpg&q=80&w=1200`

export type Difficulty = 'Fácil' | 'Media'
export type Ingredient = { name: string; qty: string; alts: string[] }
export type Recipe = {
  slug: string
  title: string
  category: string
  minutes: number
  difficulty: Difficulty
  servings: number
  cost: string
  image: string
  description: string
  allergens: string[]
  ingredients: Ingredient[]
  steps: string[]
  sticker: string
}

export const CATEGORIES = ['Todas', 'Chilenas', 'Pastas', 'Rápidas', 'Dulces', 'Vegetarianas']

export const RECIPES: Recipe[] = [
  {
    slug: 'charquican',
    title: 'Charquicán',
    category: 'Chilenas',
    minutes: 45,
    difficulty: 'Media',
    servings: 3,
    cost: '$3.500',
    image: u('photo-1628606336803-77914bbe8225'),
    description:
      'El guiso chileno de toda la vida: papas, zapallo y choclo aplastados con carne. Rinde para dos días de almuerzo.',
    allergens: ['Huevo (si lo sirves con huevo frito)'],
    sticker: 'choclo',
    ingredients: [
      { name: 'Carne molida', qty: '250 g', alts: ['Sin carne → 1 lata de porotos o lentejas cocidas, misma cantidad', 'Usa soya texturizada hidratada (80 g secos)'] },
      { name: 'Papas', qty: '4 medianas', alts: ['Sin papas → camote, misma cantidad (queda más dulce)', 'Usa 1 taza extra de zapallo'] },
      { name: 'Zapallo camote', qty: '300 g', alts: ['Sin zapallo → zanahoria rallada (2 unidades)', 'Usa 1 papa más y un poco de pimentón para el color'] },
      { name: 'Choclo', qty: '1 taza', alts: ['Sin choclo → arvejas congeladas, misma cantidad', 'Puedes omitirlo; el plato sigue funcionando'] },
      { name: 'Cebolla', qty: '1 unidad', alts: ['Sin cebolla → 1 diente de ajo extra y ½ cdta de cebolla en polvo', 'Usa la parte verde de cebollín'] },
      { name: 'Aceite', qty: '2 cdas', alts: ['Sin aceite → 1 cda de mantequilla o margarina'] },
      { name: 'Comino y orégano', qty: '1 cdta c/u', alts: ['Sin comino → pimentón ahumado o merkén, ½ cdta', 'Solo con orégano y sal también queda rico'] },
    ],
    steps: [
      'Pela y corta las papas y el zapallo en cubos medianos. Cuécelos en agua con sal por 15 min, hasta que un tenedor entre fácil.',
      'Mientras, pica la cebolla y sofríela en el aceite a fuego medio por 5 min, hasta que esté transparente.',
      'Agrega la carne molida, el comino y el orégano. Cocina revolviendo hasta que la carne cambie de color (unos 7 min).',
      'Suma el choclo y ½ taza del agua de cocción. Deja hervir 3 min.',
      'Escurre papas y zapallo, agrégalos al sartén y aplasta todo con un tenedor, dejando algunos trozos. Ajusta sal.',
      'Sirve caliente. Si quieres, ponle un huevo frito encima.',
    ],
  },
  {
    slug: 'tallarines-salsa',
    title: 'Tallarines con salsa de tomate',
    category: 'Pastas',
    minutes: 25,
    difficulty: 'Fácil',
    servings: 2,
    cost: '$2.000',
    image: u('photo-1551892374-ecf8754cf8b0'),
    description: 'Salsa casera en el tiempo que hierve el agua. Ideal para la semana de pruebas.',
    allergens: ['Gluten', 'Lácteos (queso rallado)'],
    sticker: 'tallarin',
    ingredients: [
      { name: 'Tallarines', qty: '200 g', alts: ['Sin tallarines → cualquier pasta corta, misma cantidad', 'Sin gluten → arroz o pasta de arroz'] },
      { name: 'Tomates', qty: '3 maduros', alts: ['Sin tomates → 1 lata de tomate picado o 3 cdas de salsa de tomate'] },
      { name: 'Ajo', qty: '2 dientes', alts: ['Sin ajo → ½ cdta de ajo en polvo'] },
      { name: 'Aceite de oliva', qty: '2 cdas', alts: ['Sin aceite de oliva → aceite vegetal, misma cantidad'] },
      { name: 'Queso rallado', qty: 'a gusto', alts: ['Sin queso → levadura nutricional o simplemente omitirlo'] },
    ],
    steps: [
      'Pon a hervir 2 litros de agua con sal.',
      'Pica el ajo y ralla o pica los tomates.',
      'Dora el ajo en el aceite 1 min y agrega el tomate. Cocina 10 min a fuego bajo.',
      'Cuece los tallarines según el paquete (8–10 min) y escúrrelos.',
      'Mezcla la pasta con la salsa y sirve con queso.',
    ],
  },
  {
    slug: 'arroz-huevo',
    title: 'Arroz con huevo frito',
    category: 'Rápidas',
    minutes: 20,
    difficulty: 'Fácil',
    servings: 1,
    cost: '$900',
    image: u('photo-1600688654899-379ec76aca42'),
    description: 'El clásico salvavidas: barato, rápido y reconfortante.',
    allergens: ['Huevo'],
    sticker: 'huevo',
    ingredients: [
      { name: 'Arroz', qty: '½ taza', alts: ['Sin arroz → fideos cabellito o cuscús, misma cantidad'] },
      { name: 'Huevo', qty: '1–2 unidades', alts: ['Sin huevo → tofu salteado o ½ palta en láminas'] },
      { name: 'Aceite', qty: '1 cda', alts: ['Sin aceite → mantequilla, misma cantidad'] },
      { name: 'Sal', qty: 'a gusto', alts: ['Sin sal → un chorrito de salsa de soya'] },
    ],
    steps: [
      'Lava el arroz y ponlo en una olla con 1 taza de agua y sal.',
      'Cuando hierva, tapa y baja el fuego al mínimo por 15 min.',
      'Fríe el huevo en aceite caliente hasta que la clara esté blanca y los bordes crujientes.',
      'Sirve el arroz y pon el huevo encima.',
    ],
  },
  {
    slug: 'panqueques',
    title: 'Panqueques con manjar',
    category: 'Dulces',
    minutes: 20,
    difficulty: 'Fácil',
    servings: 4,
    cost: '$1.800',
    image: u('photo-1656057205408-4a0a62cf2dfb'),
    description: 'Masa de licuadora y a la sartén. Para once o para celebrar que terminó el semestre.',
    allergens: ['Gluten', 'Huevo', 'Lácteos'],
    sticker: 'panqueque',
    ingredients: [
      { name: 'Harina', qty: '1 taza', alts: ['Sin harina común → harina integral o de avena (avena molida)'] },
      { name: 'Leche', qty: '1 ¼ taza', alts: ['Sin leche → bebida vegetal o agua con 1 cda de aceite'] },
      { name: 'Huevos', qty: '2 unidades', alts: ['Sin huevo → 1 plátano molido + 1 cdta de polvos de hornear'] },
      { name: 'Mantequilla', qty: '1 cda', alts: ['Sin mantequilla → usa aceite de oliva, misma cantidad'] },
      { name: 'Manjar', qty: 'a gusto', alts: ['Sin manjar → mermelada, miel o plátano con azúcar'] },
    ],
    steps: [
      'Licúa harina, leche, huevos y una pizca de sal hasta que no queden grumos.',
      'Deja reposar la mezcla 5 min.',
      'Calienta un sartén con un poco de mantequilla y vierte un cucharón de masa, girando para cubrir.',
      'Cocina 1 min por lado, hasta que se dore.',
      'Rellena con manjar y enrolla.',
    ],
  },
  {
    slug: 'lentejas',
    title: 'Lentejas guisadas',
    category: 'Chilenas',
    minutes: 50,
    difficulty: 'Media',
    servings: 4,
    cost: '$2.500',
    image: u('photo-1605909388460-74ec8b204127'),
    description: 'Rinde un montón, se congela bien y te deja lista para la semana.',
    allergens: ['Sin alérgenos comunes'],
    sticker: 'olla',
    ingredients: [
      { name: 'Lentejas', qty: '1 ½ taza', alts: ['Sin lentejas → garbanzos o porotos en lata'] },
      { name: 'Zanahoria', qty: '1 unidad', alts: ['Sin zanahoria → zapallo en cubos'] },
      { name: 'Cebolla', qty: '1 unidad', alts: ['Sin cebolla → puerro o cebollín'] },
      { name: 'Arroz', qty: '¼ taza', alts: ['Sin arroz → fideos cortos o papas en cubos'] },
    ],
    steps: [
      'Remoja las lentejas por 1 hora (opcional pero ayuda).',
      'Sofríe cebolla y zanahoria picadas 5 min.',
      'Agrega lentejas y 1 litro de agua. Cocina 25 min.',
      'Suma el arroz y cocina 15 min más. Ajusta sal.',
    ],
  },
  {
    slug: 'crema-zapallo',
    title: 'Crema de zapallo',
    category: 'Vegetarianas',
    minutes: 35,
    difficulty: 'Fácil',
    servings: 3,
    cost: '$1.500',
    image: u('photo-1680529670101-c8b552219c06'),
    description: 'Suave, calentita y con solo 4 ingredientes.',
    allergens: ['Lácteos (opcional)'],
    sticker: 'zapallo',
    ingredients: [
      { name: 'Zapallo', qty: '500 g', alts: ['Sin zapallo → zanahorias, misma cantidad'] },
      { name: 'Papa', qty: '1 unidad', alts: ['Sin papa → ½ taza de arroz cocido para espesar'] },
      { name: 'Cebolla', qty: '½ unidad', alts: ['Sin cebolla → ajo en polvo'] },
      { name: 'Crema o leche', qty: '¼ taza', alts: ['Sin crema → omitir o usar bebida vegetal'] },
    ],
    steps: [
      'Pica todo en cubos y cuécelo en agua con sal 20 min.',
      'Licúa con parte del agua hasta lograr la textura que te guste.',
      'Agrega la crema, ajusta sal y sirve.',
    ],
  },
  {
    slug: 'ensalada-chilena',
    title: 'Ensalada chilena con palta',
    category: 'Vegetarianas',
    minutes: 10,
    difficulty: 'Fácil',
    servings: 2,
    cost: '$1.700',
    image: u('photo-1542814880-7e62cf14b7c8'),
    description: 'Tomate, cebolla pluma y palta. Sin cocinar, acompaña todo.',
    allergens: ['Sin alérgenos comunes'],
    sticker: 'palta',
    ingredients: [
      { name: 'Tomates', qty: '2 unidades', alts: ['Sin tomate → pepino en rodajas'] },
      { name: 'Cebolla', qty: '½ unidad', alts: ['Sin cebolla → cebollín picado'] },
      { name: 'Palta', qty: '1 unidad', alts: ['Sin palta → huevo duro o choclo'] },
      { name: 'Cilantro', qty: 'a gusto', alts: ['Sin cilantro → perejil'] },
    ],
    steps: [
      'Corta la cebolla en pluma y déjala 5 min en agua fría para quitarle lo picante.',
      'Corta los tomates en gajos y la palta en cubos.',
      'Mezcla todo con aceite, sal y cilantro.',
    ],
  },
  {
    slug: 'bowl-arroz',
    title: 'Bowl de arroz y verduras',
    category: 'Rápidas',
    minutes: 25,
    difficulty: 'Fácil',
    servings: 2,
    cost: '$2.200',
    image: u('photo-1688431508895-4c2cab13b181'),
    description: 'Lo que quede en el refri + arroz = almuerzo para llevar.',
    allergens: ['Soya', 'Sésamo'],
    sticker: 'tomate',
    ingredients: [
      { name: 'Arroz cocido', qty: '2 tazas', alts: ['Sin arroz → quínoa o fideos'] },
      { name: 'Verduras de temporada', qty: '2 tazas', alts: ['Usa congeladas: mezcla primavera'] },
      { name: 'Salsa de soya', qty: '2 cdas', alts: ['Sin soya → sal + jugo de limón'] },
    ],
    steps: ['Saltea las verduras 5 min a fuego alto.', 'Agrega el arroz y la soya, mezcla 2 min.', 'Sirve en bowl.'],
  },
]

// Total del catálogo usado en la gamificación (20 recetas en la base de datos)
export const CHALLENGE = [
  ...RECIPES.map((r) => ({ slug: r.slug, title: r.title, sticker: r.sticker })),
  { slug: 'huevos-revueltos', title: 'Huevos revueltos con tomate', sticker: 'huevo2' },
  { slug: 'sopaipillas', title: 'Sopaipillas', sticker: 'pan' },
  { slug: 'porotos', title: 'Porotos con riendas', sticker: 'olla2' },
  { slug: 'pure', title: 'Puré con vienesas', sticker: 'x' },
  { slug: 'tortilla', title: 'Tortilla de verduras', sticker: 'x' },
  { slug: 'quesadillas', title: 'Quesadillas de jamón y queso', sticker: 'x' },
  { slug: 'pebre', title: 'Pebre casero', sticker: 'x' },
  { slug: 'leche-asada', title: 'Leche asada', sticker: 'x' },
  { slug: 'atun', title: 'Tallarines con atún', sticker: 'x' },
  { slug: 'cazuela', title: 'Cazuela de pollo', sticker: 'x' },
  { slug: 'budin', title: 'Budín de pan', sticker: 'x' },
  { slug: 'sandwich', title: 'Sándwich de palta y huevo', sticker: 'x' },
]

export const INITIAL_COOKED = ['tallarines-salsa', 'arroz-huevo', 'panqueques', 'ensalada-chilena', 'huevos-revueltos', 'sopaipillas', 'porotos']
export const INITIAL_SAVED = ['charquican', 'panqueques', 'crema-zapallo']

export const STICKER_NAMES: Record<string, string> = {
  tallarin: 'Fideo feliz',
  huevo: 'Huevito',
  panqueque: 'Torre dulce',
  palta: 'Palta zen',
  huevo2: 'Madrugador',
  pan: 'Pancito',
  olla2: 'Olla mágica',
  choclo: 'Choclito',
  olla: 'Guiso power',
  zapallo: 'Zapallín',
  tomate: 'Tomatín',
}
