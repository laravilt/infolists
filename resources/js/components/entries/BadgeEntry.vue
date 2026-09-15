<script setup lang="ts">
import { computed } from 'vue'
import { Badge } from '@/components/ui/badge'
import * as LucideIcons from 'lucide-vue-next'
import { formatStateValue, isEmptyState, stateKey } from '../../lib/entries'

interface BadgeEntryProps {
  label: string
  state: any
  placeholder?: string
  color?: string | null
  icon?: string | null
  colors?: Record<string, string>
  icons?: Record<string, string>
}

const props = withDefaults(defineProps<BadgeEntryProps>(), {
  placeholder: '—',
  color: null,
  icon: null,
  colors: () => ({}),
  icons: () => ({}),
})

// Scalar states (including 0 and false) can be looked up in the colors / icons maps
const currentStateKey = computed(() => stateKey(props.state))

const currentColor = computed(() => {
  const key = currentStateKey.value
  if (props.colors && key !== null && props.colors[key]) {
    return props.colors[key]
  }
  return props.color || 'secondary'
})

const currentIcon = computed(() => {
  const key = currentStateKey.value
  if (props.icons && key !== null && props.icons[key]) {
    return props.icons[key]
  }
  return props.icon
})

const lucideIconComponent = computed(() => {
  if (!currentIcon.value) return undefined

  // Convert kebab-case or any case to PascalCase for Lucide component names
  const pascalCaseName = currentIcon.value
    .split('-')
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1).toLowerCase())
    .join('')

  return (LucideIcons as any)[pascalCaseName]
})

const badgeVariant = computed(() => {
  const colorMap: Record<string, string> = {
    primary: 'default',
    success: 'success',
    danger: 'destructive',
    warning: 'warning',
    info: 'secondary',
    gray: 'secondary',
    secondary: 'secondary',
  }

  return colorMap[currentColor.value] || 'secondary'
})
</script>

<template>
  <div class="flex flex-col gap-1">
    <div class="text-sm font-medium text-foreground">
      {{ label }}
    </div>
    <div class="flex items-center">
      <Badge v-if="!isEmptyState(state)" :variant="badgeVariant" class="font-normal flex items-center gap-1.5">
        <component
          :is="lucideIconComponent"
          v-if="lucideIconComponent"
          class="h-3 w-3 shrink-0"
        />
        {{ formatStateValue(state) }}
      </Badge>
      <span v-else class="text-sm text-muted-foreground italic">
        {{ placeholder }}
      </span>
    </div>
  </div>
</template>
