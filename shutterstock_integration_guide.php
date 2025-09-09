<?php

echo "=== GUÍA DE INTEGRACIÓN CON SHUTTERSTOCK ===\n\n";

echo "Shutterstock es una excelente fuente de fotografías profesionales de biodiversidad.\n";
echo "URL de referencia: https://www.shutterstock.com/es/search/biodiversidad-peru\n\n";

echo "=== OPCIONES PARA SHUTTERSTOCK ===\n\n";

echo "1. SHUTTERSTOCK API (Recomendado para desarrolladores)\n";
echo "   - Requiere cuenta de desarrollador\n";
echo "   - Permite búsqueda programática\n";
echo "   - Descarga automática de imágenes\n";
echo "   - Documentación: https://developers.shutterstock.com/\n\n";

echo "2. SHUTTERSTOCK WEB (Para uso manual)\n";
echo "   - Buscar manualmente en: https://www.shutterstock.com/es/search/biodiversidad-peru\n";
echo "   - Descargar imágenes individuales\n";
echo "   - Obtener URLs directas de imágenes\n\n";

echo "3. SHUTTERSTOCK SUBSCRIPTION (Para uso comercial)\n";
echo "   - Suscripción mensual/anual\n";
echo "   - Descarga ilimitada de imágenes\n";
echo "   - Licencias comerciales incluidas\n\n";

echo "=== CÓDIGO DE EJEMPLO PARA SHUTTERSTOCK API ===\n\n";

echo "<?php\n";
echo "// Ejemplo de integración con Shutterstock API\n";
echo "class ShutterstockAPI {\n";
echo "    private \$apiKey;\n";
echo "    private \$baseUrl = 'https://api.shutterstock.com/v2';\n\n";
echo "    public function __construct(\$apiKey) {\n";
echo "        \$this->apiKey = \$apiKey;\n";
echo "    }\n\n";
echo "    public function searchImages(\$query, \$perPage = 20) {\n";
echo "        \$url = \$this->baseUrl . '/images/search';\n";
echo "        \$params = [\n";
echo "            'query' => \$query,\n";
echo "            'per_page' => \$perPage,\n";
echo "            'image_type' => 'photo',\n";
echo "            'orientation' => 'horizontal'\n";
echo "        ];\n\n";
echo "        \$ch = curl_init();\n";
echo "        curl_setopt(\$ch, CURLOPT_URL, \$url . '?' . http_build_query(\$params));\n";
echo "        curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);\n";
echo "        curl_setopt(\$ch, CURLOPT_HTTPHEADER, [\n";
echo "            'Authorization: Bearer ' . \$this->apiKey\n";
echo "        ]);\n\n";
echo "        \$response = curl_exec(\$ch);\n";
echo "        curl_close(\$ch);\n\n";
echo "        return json_decode(\$response, true);\n";
echo "    }\n\n";
echo "    public function downloadImage(\$imageId, \$filename) {\n";
echo "        // Implementar descarga de imagen\n";
echo "        // Requiere licencia válida\n";
echo "    }\n";
echo "}\n\n";

echo "// Uso del API\n";
echo "\$shutterstock = new ShutterstockAPI('TU_API_KEY');\n";
echo "\$results = \$shutterstock->searchImages('biodiversidad peru');\n";
echo "?>\n\n";

echo "=== FUENTES GRATUITAS ALTERNATIVAS ===\n\n";

echo "1. WIKIMEDIA COMMONS\n";
echo "   - URL: https://commons.wikimedia.org/\n";
echo "   - Licencia: Creative Commons\n";
echo "   - Uso: Gratuito con atribución\n\n";

echo "2. INATURALIST\n";
echo "   - URL: https://www.inaturalist.org/\n";
echo "   - Licencia: Variable (elegida por el usuario)\n";
echo "   - Uso: Datos científicos, identificación\n\n";

echo "3. GBIF (Global Biodiversity Information Facility)\n";
echo "   - URL: https://www.gbif.org/\n";
echo "   - Licencia: Variable\n";
echo "   - Uso: Datos científicos globales\n\n";

echo "4. FLICKR\n";
echo "   - URL: https://www.flickr.com/\n";
echo "   - Licencia: Variable (elegida por el usuario)\n";
echo "   - Uso: Encontrar fotógrafos específicos\n\n";

echo "5. UNSPLASH\n";
echo "   - URL: https://unsplash.com/\n";
echo "   - Licencia: Muy libre (comercial sin atribución)\n";
echo "   - Uso: Proyectos creativos, fondos\n\n";

echo "6. PEXELS\n";
echo "   - URL: https://www.pexels.com/\n";
echo "   - Licencia: Muy libre (comercial sin atribución)\n";
echo "   - Uso: Proyectos creativos, fondos\n\n";

echo "=== RECOMENDACIÓN ===\n\n";
echo "Para tu proyecto de biodiversidad, te recomiendo:\n\n";
echo "1. EMPEZAR CON FUENTES GRATUITAS:\n";
echo "   - Wikimedia Commons para imágenes científicas\n";
echo "   - iNaturalist para observaciones reales\n";
echo "   - Unsplash/Pexels para imágenes de alta calidad\n\n";

echo "2. CONSIDERAR SHUTTERSTOCK SI:\n";
echo "   - Tienes presupuesto para licencias\n";
echo "   - Necesitas imágenes de muy alta calidad\n";
echo "   - Planeas uso comercial\n\n";

echo "3. IMPLEMENTAR BÚSQUEDA AUTOMÁTICA:\n";
echo "   - Usar APIs de fuentes gratuitas\n";
echo "   - Crear sistema de descarga automática\n";
echo "   - Verificar licencias antes de usar\n\n";

echo "¿Te gustaría que implemente alguna de estas opciones específicas?\n";

?>
