#!/bin/bash

# Paso 1: Mostrar estado del repo
echo "🔍 Estado del repositorio:"
git status

# Paso 2: Agregar todos los archivos
echo "🟢 Agregando archivos al staging..."
git add .

# Paso 3: Pedir mensaje de commit
read -p "📝 Escribí un mensaje para el commit: " mensaje

# Paso 4: Hacer commit
git commit -m "$mensaje"

# Paso 5: Detectar rama actual
branch=$(git rev-parse --abbrev-ref HEAD)
echo "🚀 Subiendo cambios a la rama '$branch'..."
git push origin "$branch"

echo "✅ ¡Listo! Cambios enviados a GitHub."
