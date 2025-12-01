<script setup lang="ts">
import { computed } from 'vue'
import * as LucideIcons from 'lucide-vue-next'

interface IconEntryProps {
  label: string
  state: any
  placeholder?: string
  size?: string | null
  circular?: boolean
  iconColor?: string | null
}

const props = withDefaults(defineProps<IconEntryProps>(), {
  placeholder: '—',
  size: null,
  circular: false,
  iconColor: null,
})

const lucideIconComponent = computed(() => {
  if (!props.state) return undefined

  // Ensure state is a string
  const iconName = String(props.state)

  // Convert kebab-case or any case to PascalCase for Lucide component names
  const pascalCaseName = iconName
    .split('-')
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1).toLowerCase())
    .join('')

  return (LucideIcons as any)[pascalCaseName]
})

const sizeClass = computed(() => {
  switch (props.size) {
    case 'xs':
      return 'h-3 w-3'
    case 'sm':
      return 'h-4 w-4'
    case 'md':
      return 'h-5 w-5'
    case 'lg':
      return 'h-6 w-6'
    case 'xl':
      return 'h-8 w-8'
    default:
      return 'h-5 w-5'
  }
})

const colorClass = computed(() => {
  if (!props.iconColor) return 'text-foreground'

  const colorMap: Record<string, string> = {
    primary: 'text-primary',
    success: 'text-green-600',
    danger: 'text-red-600',
    warning: 'text-yellow-600',
    info: 'text-blue-600',
    gray: 'text-gray-600',
  }

  return colorMap[props.iconColor] || 'text-foreground'
})
</script>

<template>
  <div class="flex flex-col gap-1">
    <div class="text-sm font-medium text-foreground">
      {{ label }}
    </div>
    <div class="flex items-center">
      <div
        v-if="lucideIconComponent"
        :class="[
          'inline-flex items-center justify-center',
          circular && 'rounded-full bg-muted p-2',
        ]"
      >
        <component
          :is="lucideIconComponent"
          :class="[sizeClass, colorClass]"
        />
      </div>
      <span v-else class="text-sm text-muted-foreground italic">
        {{ placeholder }}
      </span>
    </div>
  </div>
</template>
