import { Fragment, type ComponentType } from 'react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { resolveIcon } from '@laravilt/support/lib/icons';
import BadgeEntry from './entries/BadgeEntry';
import CodeEntry from './entries/CodeEntry';
import ColorEntry from './entries/ColorEntry';
import IconEntry from './entries/IconEntry';
import ImageEntry from './entries/ImageEntry';
import KeyValueEntry from './entries/KeyValueEntry';
import RepeatableEntry from './entries/RepeatableEntry';
import TextEntry from './entries/TextEntry';

export interface InfoListEntry {
    component: string;
    name: string;
    label: string;
    value?: any;
    state?: any;
    schema?: InfoListEntry[];
    description?: string;
    columns?: number;
    columnSpan?: number | string | Record<string, number | string>;
    tabs?: InfoListEntry[];
    [key: string]: any;
}

export interface InfoListProps {
    schema: InfoListEntry[];
}

const componentMap: Record<string, ComponentType<any>> = {
    TextEntry,
    IconEntry,
    ImageEntry,
    ColorEntry,
    CodeEntry,
    KeyValueEntry,
    RepeatableEntry,
    BadgeEntry,
};

const getEntryComponent = (componentType: string): ComponentType<any> => {
    return componentMap[componentType] || TextEntry;
};

const LAYOUT_TYPES = ['section', 'grid', 'tabs'];

// Helper to check component type (case insensitive)
const isComponent = (item: InfoListEntry, type: string) => {
    return (item.component || '').toLowerCase() === type.toLowerCase();
};

const getGridClass = (columns?: number): string => {
    if (!columns || columns === 1) return 'md:grid-cols-1';
    if (columns === 2) return 'md:grid-cols-2';
    if (columns === 3) return 'md:grid-cols-3';
    if (columns === 4) return 'md:grid-cols-4';
    if (columns === 5) return 'md:grid-cols-5';
    if (columns === 6) return 'md:grid-cols-6';

    return 'md:grid-cols-1';
};

const getColumnSpanClass = (columnSpan?: number | string | Record<string, number | string>): string => {
    if (!columnSpan) return '';

    // Handle 'full' string
    if (columnSpan === 'full') return 'col-span-full';

    // Handle integer
    if (typeof columnSpan === 'number') {
        return `md:col-span-${columnSpan}`;
    }

    // Handle responsive object: { md: 2, xl: 4 }
    if (typeof columnSpan === 'object') {
        const classes: string[] = [];

        // Handle default key (for sm and below)
        if ('default' in columnSpan) {
            const defaultSpan = columnSpan.default;

            if (defaultSpan === 'full') {
                classes.push('col-span-full');
            } else {
                classes.push(`col-span-${defaultSpan}`);
            }
        }

        // Handle responsive breakpoints
        for (const [breakpoint, span] of Object.entries(columnSpan)) {
            if (breakpoint === 'default') continue;

            if (span === 'full') {
                classes.push(`${breakpoint}:col-span-full`);
            } else {
                classes.push(`${breakpoint}:col-span-${span}`);
            }
        }

        return classes.join(' ');
    }

    return '';
};

/** `<component :is="getEntryComponent(entry.component)" :class="…" v-bind="{ ...entry, state: entry.value ?? entry.state }" />` */
function renderEntry(entry: InfoListEntry, key: string | number) {
    const Entry = getEntryComponent(entry.component);

    return <Entry key={key} {...entry} state={entry.value ?? entry.state} className={getColumnSpanClass(entry.columnSpan)} />;
}

function SectionHeader({ item }: { item: InfoListEntry }) {
    if (!(item.label || item.heading)) {
        return null;
    }

    const Icon = item.icon ? resolveIcon(item.icon) : null;

    return (
        <header className="px-6 py-4 border-b">
            <div className="flex items-center gap-3">
                {item.icon && Icon && (
                    <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary flex-shrink-0">
                        <Icon className="h-5 w-5" />
                    </div>
                )}
                <div className="flex-1 min-w-0">
                    <h3 className="leading-none font-semibold">{item.label || item.heading}</h3>
                    {item.description && <p className="mt-1 text-sm text-muted-foreground">{item.description}</p>}
                </div>
            </div>
        </header>
    );
}

export default function InfoList({ schema }: InfoListProps) {
    // Separate layout components from entries
    const layoutComponents =
        !schema || !Array.isArray(schema)
            ? []
            : schema.filter((item) => LAYOUT_TYPES.includes((item.component || '').toLowerCase()));

    const entries =
        !schema || !Array.isArray(schema)
            ? []
            : schema.filter((item) => !LAYOUT_TYPES.includes((item.component || '').toLowerCase()));

    return (
        <div className="space-y-6">
            {/* Render layout components (Section, Grid, Tabs) */}
            {layoutComponents.map((layout) => (
                <Fragment key={layout.label || layout.name}>
                    {isComponent(layout, 'section') ? (
                        /* Section Component */
                        <div className="bg-card text-card-foreground rounded-xl border shadow-sm">
                            <SectionHeader item={layout} />
                            <div className="p-6">
                                <div className="space-y-6">
                                    {(layout.schema ?? []).map((item) => (
                                        <Fragment key={item.name}>
                                            {isComponent(item, 'grid') ? (
                                                /* Nested Grid within Section */
                                                <div className={`grid gap-6 ${getGridClass(item.columns)}`}>
                                                    {(item.schema ?? []).map((entry) => renderEntry(entry, entry.name))}
                                                </div>
                                            ) : (
                                                /* Direct Entry within Section */
                                                renderEntry(item, item.name)
                                            )}
                                        </Fragment>
                                    ))}
                                </div>
                            </div>
                        </div>
                    ) : isComponent(layout, 'grid') ? (
                        /* Grid Component */
                        <div className={`grid gap-6 ${getGridClass(layout.columns)}`}>
                            {(layout.schema ?? []).map((entry) => renderEntry(entry, entry.name))}
                        </div>
                    ) : isComponent(layout, 'tabs') ? (
                        /* Tabs Component */
                        <Tabs defaultValue={layout.tabs?.[0]?.label || 'tab-0'}>
                            <TabsList>
                                {(layout.tabs ?? []).map((tab, index) => (
                                    <TabsTrigger key={tab.label || `tab-${index}`} value={tab.label || `tab-${index}`}>
                                        {tab.label}
                                    </TabsTrigger>
                                ))}
                            </TabsList>
                            {(layout.tabs ?? []).map((tab, index) => (
                                <TabsContent key={tab.label || `tab-${index}`} value={tab.label || `tab-${index}`} className="mt-6">
                                    <div className="space-y-6">
                                        {/* Recursively render layout components within tabs */}
                                        {(tab.schema ?? []).map((item) => (
                                            <Fragment key={item.name}>
                                                {isComponent(item, 'section') ? (
                                                    /* Section within Tab */
                                                    <div className="bg-card text-card-foreground rounded-xl border shadow-sm">
                                                        <SectionHeader item={item} />
                                                        <div className="p-6">
                                                            <div className={`grid gap-6 ${getGridClass(item.columns)}`}>
                                                                {(item.schema ?? []).map((entry) => renderEntry(entry, entry.name))}
                                                            </div>
                                                        </div>
                                                    </div>
                                                ) : isComponent(item, 'grid') ? (
                                                    /* Grid within Tab */
                                                    <div className={`grid gap-6 ${getGridClass(item.columns)}`}>
                                                        {(item.schema ?? []).map((entry) => renderEntry(entry, entry.name))}
                                                    </div>
                                                ) : (
                                                    /* Regular entries within Tab */
                                                    renderEntry(item, item.name)
                                                )}
                                            </Fragment>
                                        ))}
                                    </div>
                                </TabsContent>
                            ))}
                        </Tabs>
                    ) : null}
                </Fragment>
            ))}

            {/* If there are no layout components, render entries directly without card wrapper */}
            {layoutComponents.length === 0 && entries.length > 0 && (
                <div className="grid gap-6">{entries.map((entry) => renderEntry(entry, entry.name))}</div>
            )}
        </div>
    );
}

export { InfoList };
