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
import RepeatableEntry from './RepeatableEntry.vue'
import { getValueAtPath, normalizeEntryProps } from '../../lib/entries'

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

// Items the user toggled away from the default (`collapsed`). Items added later follow the default.
const toggledItems = ref<Set<number>>(new Set())

const toggleItem = (index: number) => {
  const next = new Set(toggledItems.value)
  if (next.has(index)) {
    next.delete(index)
  } else {
    next.add(index)
  }
  toggledItems.value = next
}

const isExpanded = (index: number) => {
  return props.collapsed ? toggledItems.value.has(index) : !toggledItems.value.has(index)
}

const componentMap: Record<string, any> = {
  TextEntry,
  IconEntry,
  ImageEntry,
  ColorEntry,
  CodeEntry,
  KeyValueEntry,
  BadgeEntry,
  RepeatableEntry,
  // The server sends snake_case types (Laravilt\Support\Component::getComponentType)
  text_entry: TextEntry,
  icon_entry: IconEntry,
  image_entry: ImageEntry,
  color_entry: ColorEntry,
  code_entry: CodeEntry,
  key_value_entry: KeyValueEntry,
  badge_entry: BadgeEntry,
  repeatable_entry: RepeatableEntry,
}

const getEntryComponent = (componentType: string) => {
  return componentMap[componentType] || TextEntry
}

// Props for a nested entry: snake_case keys also exposed as camelCase, state read from the item (dot notation)
const entryProps = (entry: any, item: any) => {
  return { ...normalizeEntryProps(entry), state: getValueAtPath(item, entry.name) }
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
          role="button"
          tabindex="0"
          :aria-expanded="String(isExpanded(index))"
          @click="toggleItem(index)"
          @keydown.enter.prevent="toggleItem(index)"
          @keydown.space.prevent="toggleItem(index)"
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
            v-bind="entryProps(entry, item)"
          />
        </div>
      </div>
    </div>
    <span v-else class="text-sm text-muted-foreground italic">
      {{ emptyMessage }}
    </span>
  </div>
</template>
