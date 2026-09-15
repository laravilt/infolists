/**
 * Vue's `{{ value }}` interpolation semantics: null/undefined → '', objects/arrays → pretty JSON, otherwise String().
 * React would throw on plain objects as children, so entries render state through this.
 */
export function toDisplayString(value: any): string {
    if (value === null || value === undefined) {
        return '';
    }

    if (typeof value === 'object') {
        try {
            return JSON.stringify(value, null, 2);
        } catch {
            return String(value);
        }
    }

    return String(value);
}
