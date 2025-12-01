<script setup lang="ts">
import { ref, computed } from 'vue'
import { ChevronDown, ChevronRight } from 'lucide-vue-next'
import TextEntry from './TextEntry.vue'
import IconEntry from './IconEntry.vue'
import ImageEntry from './ImageEntry.vue'
import ColorEntry from './ColorEntry.vue'
import CodeEntry from './CodeEntry.vue'
import KeyValueEntry from './KeyValueEntry.vue'
import BadgeEntry from './BadgeEntry.vue'

interface RepeatableEntryProps {
  label: string
  state: any[]
  placeholder?: string
  schema: any[]
  collapsible?: boolean
  collapsed?: boolean
  emptyMessage?: string
}

const props = withDefaults(defineProps<RepeatableEntryProps>(), {
  placeholder: '—',
  schema: () => [],
  collapsible: false,
  collapsed: false,
  emptyMessage: 'No items',
})

const expandedItems = ref<Set<number>>(new Set())

// If collapsed by default, start with all items collapsed
if (!props.collapsed && Array.isArray(props.state)) {
  props.state.forEach((_, index) => {
    expandedItems.value.add(index)
  })
}

const toggleItem = (index: number) => {
  if (expandedItems.value.has(index)) {
    expandedItems.value.delete(index)
  } else {
    expandedItems.value.add(index)
  }
}

const isExpanded = (index: number) => {
  return expandedItems.value.has(index)
}

const getEntryComponent = (componentType: string) => {
  const componentMap: Record<string, any> = {
    TextEntry,
    IconEntry,
    ImageEntry,
    ColorEntry,
    CodeEntry,
    KeyValueEntry,
    BadgeEntry,
  }

  return componentMap[componentType] || TextEntry
}

const items = computed(() => {
  if (!Array.isArray(props.state)) {
    return []
  }

  return props.state
})
</script>

<template>
  <div class="flex flex-col gap-1">
    <div class="text-sm font-medium text-foreground">
      {{ label }}
    </div>
    <div v-if="items.length > 0" class="flex flex-col gap-2">
      <div
        v-for="(item, index) in items"
        :key="index"
        class="rounded-md border border-border overflow-hidden"
      >
        <div
          v-if="collapsible"
          class="flex items-center justify-between px-4 py-3 bg-muted/50 cursor-pointer hover:bg-muted"
          @click="toggleItem(index)"
        >
          <span class="text-sm font-medium text-foreground">
            Item {{ index + 1 }}
          </span>
          <component
            :is="isExpanded(index) ? ChevronDown : ChevronRight"
            class="h-4 w-4 text-muted-foreground"
          />
        </div>
        <div
          v-if="!collapsible || isExpanded(index)"
          class="p-4 space-y-4"
        >
          <component
            v-for="(entry, entryIndex) in schema"
            :key="entryIndex"
            :is="getEntryComponent(entry.component)"
            v-bind="{ ...entry, state: item[entry.name] }"
          />
        </div>
      </div>
    </div>
    <span v-else class="text-sm text-muted-foreground italic">
      {{ emptyMessage }}
    </span>
  </div>
</template>
