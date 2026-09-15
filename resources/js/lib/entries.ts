/**
 * Helpers shared by the infolist entries and InfoList.
 *
 * Keep this file identical to resources/react/lib/entries.ts (Vue / React parity).
 *
 * Every Tailwind class below is written out literally: Tailwind only generates classes it can find
 * as complete strings in the scanned sources, so classes must never be built with template strings.
 */

/**
 * Whether an entry state is "empty" (renders the placeholder).
 * `0` and `false` are real values and are NOT empty.
 */
export const isEmptyState = (value: unknown): boolean =>
    value === null || value === undefined || value === '' || (Array.isArray(value) && value.length === 0)

/**
 * Text for a state value: arrays are joined (`separator`, default `, `), objects are JSON,
 * everything else goes through String() (so `0` → "0" and `false` → "false").
 */
export const formatStateValue = (value: unknown, separator: string = ', '): string => {
    if (value === null || value === undefined) {
        return ''
    }

    if (Array.isArray(value)) {
        return value
            .filter((item) => item !== null && item !== undefined)
            .map((item) => formatStateValue(item, separator))
            .join(separator)
    }

    if (typeof value === 'object') {
        try {
            return JSON.stringify(value)
        } catch {
            return String(value)
        }
    }

    return String(value)
}

/** Key used to look a state up in a `colors` / `icons` map (only scalar states can match). */
export const stateKey = (value: unknown): string | null => {
    if (typeof value === 'string' || typeof value === 'number') {
        return String(value)
    }

    if (typeof value === 'boolean') {
        // PHP casts `true` / `false` array keys to 1 / 0
        return value ? '1' : '0'
    }

    return null
}

const toCamelCase = (key: string): string => key.replace(/_([a-z0-9])/g, (_match, char: string) => char.toUpperCase())

/**
 * Entry props with snake_case keys (`empty_message`, `default_image`, `key_label`, …) also exposed under
 * their camelCase name, which is what the entry components declare. camelCase keys win when both exist.
 */
export const normalizeEntryProps = <T extends Record<string, any>>(entry: T): T => {
    if (!entry || typeof entry !== 'object') {
        return entry
    }

    const normalized: Record<string, any> = { ...entry }

    for (const [key, value] of Object.entries(entry)) {
        if (key.includes('_')) {
            const camel = toCamelCase(key)

            if (!(camel in entry)) {
                normalized[camel] = value
            }
        }
    }

    return normalized as T
}

/** `data_get`-style lookup: the whole key first, then dot notation (`author.name`). */
export const getValueAtPath = (item: unknown, path: string | null | undefined): any => {
    if (item === null || item === undefined || typeof item !== 'object' || !path) {
        return undefined
    }

    const record = item as Record<string, any>

    if (path in record) {
        return record[path]
    }

    return path.split('.').reduce<any>((current, segment) => {
        if (current === null || current === undefined || typeof current !== 'object') {
            return undefined
        }

        return current[segment]
    }, record)
}

type Breakpoint = 'default' | 'sm' | 'md' | 'lg' | 'xl' | '2xl'

const BREAKPOINTS: Breakpoint[] = ['default', 'sm', 'md', 'lg', 'xl', '2xl']

const isBreakpoint = (value: string): value is Breakpoint => (BREAKPOINTS as string[]).includes(value)

const GRID_COLS: Record<Breakpoint, Record<string, string>> = {
    default: {
        1: 'grid-cols-1', 2: 'grid-cols-2', 3: 'grid-cols-3', 4: 'grid-cols-4', 5: 'grid-cols-5', 6: 'grid-cols-6',
        7: 'grid-cols-7', 8: 'grid-cols-8', 9: 'grid-cols-9', 10: 'grid-cols-10', 11: 'grid-cols-11', 12: 'grid-cols-12',
    },
    sm: {
        1: 'sm:grid-cols-1', 2: 'sm:grid-cols-2', 3: 'sm:grid-cols-3', 4: 'sm:grid-cols-4', 5: 'sm:grid-cols-5', 6: 'sm:grid-cols-6',
        7: 'sm:grid-cols-7', 8: 'sm:grid-cols-8', 9: 'sm:grid-cols-9', 10: 'sm:grid-cols-10', 11: 'sm:grid-cols-11', 12: 'sm:grid-cols-12',
    },
    md: {
        1: 'md:grid-cols-1', 2: 'md:grid-cols-2', 3: 'md:grid-cols-3', 4: 'md:grid-cols-4', 5: 'md:grid-cols-5', 6: 'md:grid-cols-6',
        7: 'md:grid-cols-7', 8: 'md:grid-cols-8', 9: 'md:grid-cols-9', 10: 'md:grid-cols-10', 11: 'md:grid-cols-11', 12: 'md:grid-cols-12',
    },
    lg: {
        1: 'lg:grid-cols-1', 2: 'lg:grid-cols-2', 3: 'lg:grid-cols-3', 4: 'lg:grid-cols-4', 5: 'lg:grid-cols-5', 6: 'lg:grid-cols-6',
        7: 'lg:grid-cols-7', 8: 'lg:grid-cols-8', 9: 'lg:grid-cols-9', 10: 'lg:grid-cols-10', 11: 'lg:grid-cols-11', 12: 'lg:grid-cols-12',
    },
    xl: {
        1: 'xl:grid-cols-1', 2: 'xl:grid-cols-2', 3: 'xl:grid-cols-3', 4: 'xl:grid-cols-4', 5: 'xl:grid-cols-5', 6: 'xl:grid-cols-6',
        7: 'xl:grid-cols-7', 8: 'xl:grid-cols-8', 9: 'xl:grid-cols-9', 10: 'xl:grid-cols-10', 11: 'xl:grid-cols-11', 12: 'xl:grid-cols-12',
    },
    '2xl': {
        1: '2xl:grid-cols-1', 2: '2xl:grid-cols-2', 3: '2xl:grid-cols-3', 4: '2xl:grid-cols-4', 5: '2xl:grid-cols-5', 6: '2xl:grid-cols-6',
        7: '2xl:grid-cols-7', 8: '2xl:grid-cols-8', 9: '2xl:grid-cols-9', 10: '2xl:grid-cols-10', 11: '2xl:grid-cols-11', 12: '2xl:grid-cols-12',
    },
}

const COL_SPAN: Record<Breakpoint, Record<string, string>> = {
    default: {
        1: 'col-span-1', 2: 'col-span-2', 3: 'col-span-3', 4: 'col-span-4', 5: 'col-span-5', 6: 'col-span-6',
        7: 'col-span-7', 8: 'col-span-8', 9: 'col-span-9', 10: 'col-span-10', 11: 'col-span-11', 12: 'col-span-12', full: 'col-span-full',
    },
    sm: {
        1: 'sm:col-span-1', 2: 'sm:col-span-2', 3: 'sm:col-span-3', 4: 'sm:col-span-4', 5: 'sm:col-span-5', 6: 'sm:col-span-6',
        7: 'sm:col-span-7', 8: 'sm:col-span-8', 9: 'sm:col-span-9', 10: 'sm:col-span-10', 11: 'sm:col-span-11', 12: 'sm:col-span-12', full: 'sm:col-span-full',
    },
    md: {
        1: 'md:col-span-1', 2: 'md:col-span-2', 3: 'md:col-span-3', 4: 'md:col-span-4', 5: 'md:col-span-5', 6: 'md:col-span-6',
        7: 'md:col-span-7', 8: 'md:col-span-8', 9: 'md:col-span-9', 10: 'md:col-span-10', 11: 'md:col-span-11', 12: 'md:col-span-12', full: 'md:col-span-full',
    },
    lg: {
        1: 'lg:col-span-1', 2: 'lg:col-span-2', 3: 'lg:col-span-3', 4: 'lg:col-span-4', 5: 'lg:col-span-5', 6: 'lg:col-span-6',
        7: 'lg:col-span-7', 8: 'lg:col-span-8', 9: 'lg:col-span-9', 10: 'lg:col-span-10', 11: 'lg:col-span-11', 12: 'lg:col-span-12', full: 'lg:col-span-full',
    },
    xl: {
        1: 'xl:col-span-1', 2: 'xl:col-span-2', 3: 'xl:col-span-3', 4: 'xl:col-span-4', 5: 'xl:col-span-5', 6: 'xl:col-span-6',
        7: 'xl:col-span-7', 8: 'xl:col-span-8', 9: 'xl:col-span-9', 10: 'xl:col-span-10', 11: 'xl:col-span-11', 12: 'xl:col-span-12', full: 'xl:col-span-full',
    },
    '2xl': {
        1: '2xl:col-span-1', 2: '2xl:col-span-2', 3: '2xl:col-span-3', 4: '2xl:col-span-4', 5: '2xl:col-span-5', 6: '2xl:col-span-6',
        7: '2xl:col-span-7', 8: '2xl:col-span-8', 9: '2xl:col-span-9', 10: '2xl:col-span-10', 11: '2xl:col-span-11', 12: '2xl:col-span-12', full: '2xl:col-span-full',
    },
}

/** Grid classes for `columns`: a number applies from `md` up; an object sets each breakpoint. */
export const getGridClass = (columns: unknown): string => {
    if (columns && typeof columns === 'object') {
        const classes: string[] = []

        for (const [breakpoint, count] of Object.entries(columns as Record<string, unknown>)) {
            const cls = isBreakpoint(breakpoint) ? GRID_COLS[breakpoint][String(count)] : undefined

            if (cls) {
                classes.push(cls)
            }
        }

        return classes.length > 0 ? classes.join(' ') : GRID_COLS.md[1]
    }

    return GRID_COLS.md[String(columns ?? 1)] ?? GRID_COLS.md[1]
}

/**
 * Column-span classes: `'full'` spans every column, a number applies from `md` up,
 * an object sets each breakpoint (`default` has no prefix).
 */
export const getColumnSpanClass = (columnSpan: unknown): string => {
    if (columnSpan === null || columnSpan === undefined || columnSpan === '' || columnSpan === 0) {
        return ''
    }

    if (columnSpan === 'full') {
        return COL_SPAN.default.full
    }

    if (typeof columnSpan === 'number' || typeof columnSpan === 'string') {
        return COL_SPAN.md[String(columnSpan)] ?? ''
    }

    if (typeof columnSpan === 'object') {
        const classes: string[] = []

        for (const [breakpoint, span] of Object.entries(columnSpan as Record<string, unknown>)) {
            const cls = isBreakpoint(breakpoint) ? COL_SPAN[breakpoint][String(span)] : undefined

            if (cls) {
                classes.push(cls)
            }
        }

        return classes.join(' ')
    }

    return ''
}
