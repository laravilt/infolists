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
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'

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
  return props.schema.filter(item => ['Section', 'Grid', 'Tabs'].includes(item.component))
})

const entries = computed(() => {
  return props.schema.filter(item => !['Section', 'Grid', 'Tabs'].includes(item.component))
})

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
</script>

<template>
  <div class="space-y-6">
    <!-- Render layout components (Section, Grid, Tabs) -->
    <template v-for="layout in layoutComponents" :key="layout.label || layout.name">
      <!-- Section Component -->
      <Card v-if="layout.component === 'Section'">
        <CardHeader v-if="layout.label">
          <CardTitle>{{ layout.label }}</CardTitle>
          <CardDescription v-if="layout.description">
            {{ layout.description }}
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="grid gap-6" :class="getGridClass(layout.columns)">
            <component
              v-for="entry in layout.schema"
              :key="entry.name"
              :is="getEntryComponent(entry.component)"
              :class="getColumnSpanClass(entry.columnSpan)"
              v-bind="{ ...entry, state: entry.value ?? entry.state }"
            />
          </div>
        </CardContent>
      </Card>

      <!-- Grid Component -->
      <div v-else-if="layout.component === 'Grid'" class="grid gap-6" :class="getGridClass(layout.columns)">
        <component
          v-for="entry in layout.schema"
          :key="entry.name"
          :is="getEntryComponent(entry.component)"
          :class="getColumnSpanClass(entry.columnSpan)"
          v-bind="{ ...entry, state: entry.value ?? entry.state }"
        />
      </div>

      <!-- Tabs Component -->
      <Tabs v-else-if="layout.component === 'Tabs'" :default-value="layout.tabs?.[0]?.label || 'tab-0'">
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
          <div class="grid gap-6">
            <component
              v-for="entry in tab.schema"
              :key="entry.name"
              :is="getEntryComponent(entry.component)"
              :class="getColumnSpanClass(entry.columnSpan)"
              v-bind="{ ...entry, state: entry.value ?? entry.state }"
            />
          </div>
        </TabsContent>
      </Tabs>
    </template>

    <!-- If there are no layout components, render entries directly in a card -->
    <Card v-if="layoutComponents.length === 0 && entries.length > 0">
      <CardContent class="pt-6">
        <div class="grid gap-6">
          <component
            v-for="entry in entries"
            :key="entry.name"
            :is="getEntryComponent(entry.component)"
            :class="getColumnSpanClass(entry.columnSpan)"
            v-bind="{ ...entry, state: entry.value ?? entry.state }"
          />
        </div>
      </CardContent>
    </Card>
  </div>
</template>
