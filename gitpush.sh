# Paso 1: Mostrar estado del repo
echo "🔍 Estado del repositorio:"
git status

# Paso 2: Agregar todos los archivos
echo "🟢 Agregando archivos al staging..."
git add .

# Paso 3: Hacer commit
git commit -m "cambios de resenias"

# Paso 5: Detectar rama actual
git rev-parse --abbrev-ref HEAD
echo "🚀 Subiendo cambios a la rama principal"
git push origin main

echo "✅ ¡Listo! Cambios enviados a GitHub."
