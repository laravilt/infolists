<script setup lang="ts">
import { computed } from 'vue'
import TextEntry from './entries/TextEntry.vue'
import IconEntry from './entries/IconEntry.vue'
import ImageEntry from './entries/ImageEntry.vue'
import ColorEntry from './entries/ColorEntry.vue'
import CodeEntry from './entries/CodeEntry.vue'
import KeyValueEntry from './entries/KeyValueEntry.vue'
import RepeatableEntry from './entries/RepeatableEntry.vue'
import BadgeEntry from './entries/BadgeEntry.vue'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import * as LucideIcons from 'lucide-vue-next'

interface InfoListEntry {
  component: string
  name: string
  label: string
  value?: any
  state?: any
  schema?: InfoListEntry[]
  description?: string
  columns?: number
  columnSpan?: number | string | Record<string, number | string>
  tabs?: InfoListEntry[]
  [key: string]: any
}

interface InfoListProps {
  schema: InfoListEntry[]
}

const props = defineProps<InfoListProps>()

const getEntryComponent = (componentType: string) => {
  const componentMap: Record<string, any> = {
    TextEntry,
    IconEntry,
    ImageEntry,
    ColorEntry,
    CodeEntry,
    KeyValueEntry,
    RepeatableEntry,
    BadgeEntry,
  }

  return componentMap[componentType] || TextEntry
}

// Separate layout components from entries
const layoutComponents = computed(() => {
  if (!props.schema || !Array.isArray(props.schema)) return []
  return props.schema.filter(item => {
    const componentType = (item.component || '').toLowerCase()
    return ['section', 'grid', 'tabs'].includes(componentType)
  })
})

const entries = computed(() => {
  if (!props.schema || !Array.isArray(props.schema)) return []
  return props.schema.filter(item => {
    const componentType = (item.component || '').toLowerCase()
    return !['section', 'grid', 'tabs'].includes(componentType)
  })
})

// Helper to check component type (case insensitive)
const isComponent = (item: InfoListEntry, type: string) => {
  return (item.component || '').toLowerCase() === type.toLowerCase()
}

const getGridClass = (columns?: number) => {
  if (!columns || columns === 1) return 'md:grid-cols-1'
  if (columns === 2) return 'md:grid-cols-2'
  if (columns === 3) return 'md:grid-cols-3'
  if (columns === 4) return 'md:grid-cols-4'
  if (columns === 5) return 'md:grid-cols-5'
  if (columns === 6) return 'md:grid-cols-6'
  return 'md:grid-cols-1'
}

const getColumnSpanClass = (columnSpan?: number | string | Record<string, number | string>) => {
  if (!columnSpan) return ''

  // Handle 'full' string
  if (columnSpan === 'full') return 'col-span-full'

  // Handle integer
  if (typeof columnSpan === 'number') {
    return `md:col-span-${columnSpan}`
  }

  // Handle responsive object: { md: 2, xl: 4 }
  if (typeof columnSpan === 'object') {
    const classes: string[] = []

    // Handle default key (for sm and below)
    if ('default' in columnSpan) {
      const defaultSpan = columnSpan.default
      if (defaultSpan === 'full') {
        classes.push('col-span-full')
      } else {
        classes.push(`col-span-${defaultSpan}`)
      }
    }

    // Handle responsive breakpoints
    for (const [breakpoint, span] of Object.entries(columnSpan)) {
      if (breakpoint === 'default') continue

      if (span === 'full') {
        classes.push(`${breakpoint}:col-span-full`)
      } else {
        classes.push(`${breakpoint}:col-span-${span}`)
      }
    }

    return classes.join(' ')
  }

  return ''
}

// Convert kebab-case or snake_case icon names to PascalCase for lucide-vue-next
const getIconComponent = (iconName: string) => {
  if (!iconName) return null

  // Convert formats like 'user', 'id-card', 'map-pin' to PascalCase
  const pascalCase = iconName
    .split(/[-_]/)
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join('')

  return (LucideIcons as any)[pascalCase] || null
}
</script>

<template>
  <div class="space-y-6">
    <!-- Render layout components (Section, Grid, Tabs) -->
    <template v-for="layout in layoutComponents" :key="layout.label || layout.name">
      <!-- Section Component -->
      <div v-if="isComponent(layout, 'section')" class="bg-card text-card-foreground rounded-xl border shadow-sm">
        <header v-if="layout.label || layout.heading" class="px-6 py-4 border-b">
          <div class="flex items-center gap-3">
            <div
              v-if="layout.icon && getIconComponent(layout.icon)"
              class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary flex-shrink-0"
            >
              <component
                :is="getIconComponent(layout.icon)"
                class="h-5 w-5"
              />
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="leading-none font-semibold">{{ layout.label || layout.heading }}</h3>
              <p v-if="layout.description" class="mt-1 text-sm text-muted-foreground">
                {{ layout.description }}
              </p>
            </div>
          </div>
        </header>
        <div class="p-6">
          <div class="space-y-6">
            <template v-for="item in layout.schema" :key="item.name">
              <!-- Nested Grid within Section -->
              <div v-if="isComponent(item, 'grid')" class="grid gap-6" :class="getGridClass(item.columns)">
                <component
                  v-for="entry in item.schema"
                  :key="entry.name"
                  :is="getEntryComponent(entry.component)"
                  :class="getColumnSpanClass(entry.columnSpan)"
                  v-bind="{ ...entry, state: entry.value ?? entry.state }"
                />
              </div>
              <!-- Direct Entry within Section -->
              <component
                v-else
                :is="getEntryComponent(item.component)"
                :class="getColumnSpanClass(item.columnSpan)"
                v-bind="{ ...item, state: item.value ?? item.state }"
              />
            </template>
          </div>
        </div>
      </div>

      <!-- Grid Component -->
      <div v-else-if="isComponent(layout, 'grid')" class="grid gap-6" :class="getGridClass(layout.columns)">
        <component
          v-for="entry in layout.schema"
          :key="entry.name"
          :is="getEntryComponent(entry.component)"
          :class="getColumnSpanClass(entry.columnSpan)"
          v-bind="{ ...entry, state: entry.value ?? entry.state }"
        />
      </div>

      <!-- Tabs Component -->
      <Tabs v-else-if="isComponent(layout, 'tabs')" :default-value="layout.tabs?.[0]?.label || 'tab-0'">
        <TabsList>
          <TabsTrigger
            v-for="(tab, index) in layout.tabs"
            :key="tab.label || `tab-${index}`"
            :value="tab.label || `tab-${index}`"
          >
            {{ tab.label }}
          </TabsTrigger>
        </TabsList>
        <TabsContent
          v-for="(tab, index) in layout.tabs"
          :key="tab.label || `tab-${index}`"
          :value="tab.label || `tab-${index}`"
          class="mt-6"
        >
          <div class="space-y-6">
            <!-- Recursively render layout components within tabs -->
            <template v-for="item in tab.schema" :key="item.name">
              <!-- Section within Tab -->
              <div v-if="isComponent(item, 'section')" class="bg-card text-card-foreground rounded-xl border shadow-sm">
                <header v-if="item.label || item.heading" class="px-6 py-4 border-b">
                  <div class="flex items-center gap-3">
                    <div
                      v-if="item.icon && getIconComponent(item.icon)"
                      class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary flex-shrink-0"
                    >
                      <component
                        :is="getIconComponent(item.icon)"
                        class="h-5 w-5"
                      />
                    </div>
                    <div class="flex-1 min-w-0">
                      <h3 class="leading-none font-semibold">{{ item.label || item.heading }}</h3>
                      <p v-if="item.description" class="mt-1 text-sm text-muted-foreground">
                        {{ item.description }}
                      </p>
                    </div>
                  </div>
                </header>
                <div class="p-6">
                  <div class="grid gap-6" :class="getGridClass(item.columns)">
                    <component
                      v-for="entry in item.schema"
                      :key="entry.name"
                      :is="getEntryComponent(entry.component)"
                      :class="getColumnSpanClass(entry.columnSpan)"
                      v-bind="{ ...entry, state: entry.value ?? entry.state }"
                    />
                  </div>
                </div>
              </div>

              <!-- Grid within Tab -->
              <div v-else-if="isComponent(item, 'grid')" class="grid gap-6" :class="getGridClass(item.columns)">
                <component
                  v-for="entry in item.schema"
                  :key="entry.name"
                  :is="getEntryComponent(entry.component)"
                  :class="getColumnSpanClass(entry.columnSpan)"
                  v-bind="{ ...entry, state: entry.value ?? entry.state }"
                />
              </div>

              <!-- Regular entries within Tab -->
              <component
                v-else
                :is="getEntryComponent(item.component)"
                :class="getColumnSpanClass(item.columnSpan)"
                v-bind="{ ...item, state: item.value ?? item.state }"
              />
            </template>
          </div>
        </TabsContent>
      </Tabs>
    </template>

    <!-- If there are no layout components, render entries directly without card wrapper -->
    <div v-if="layoutComponents.length === 0 && entries.length > 0" class="grid gap-6">
      <component
        v-for="entry in entries"
        :key="entry.name"
        :is="getEntryComponent(entry.component)"
        :class="getColumnSpanClass(entry.columnSpan)"
        v-bind="{ ...entry, state: entry.value ?? entry.state }"
      />
    </div>
  </div>
</template>
