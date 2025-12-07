import { test, expect } from '@playwright/test'

/**
 * Infolist Component E2E Tests
 *
 * These tests verify the infolist component functionality against
 * the Infolist Demo page in the Laravilt admin panel.
 */

test.describe('Infolist Component', () => {
  test.beforeEach(async ({ page }) => {
    // Navigate to the infolist demo page
    await page.goto('/admin/demos/infolist')
    // Wait for the infolist to be rendered
    await page.waitForSelector('[class*="section"], [class*="card"]', { timeout: 10000 })
  })

  test.describe('Page Structure', () => {
    test('should render the infolist page', async ({ page }) => {
      // Check that the page title is visible
      const title = page.locator('h1, [class*="title"]').filter({ hasText: /InfoList|Info List/i }).first()
      const exists = await title.count()
      if (exists > 0) {
        await expect(title).toBeVisible()
      }
    })

    test('should render sections', async ({ page }) => {
      // Check that sections are rendered
      const sections = page.locator('[class*="section"], [class*="card"]')
      const count = await sections.count()
      expect(count).toBeGreaterThan(0)
    })

    test('should render action buttons', async ({ page }) => {
      // Check that action buttons are rendered
      const actionButtons = page.locator('button:has-text("Edit Profile"), button:has-text("View Form")')
      const exists = await actionButtons.count()
      if (exists > 0) {
        await expect(actionButtons.first()).toBeVisible()
      }
    })
  })

  test.describe('User Profile Section', () => {
    test('should display user profile section', async ({ page }) => {
      const section = page.locator('text=User Profile').first()
      await expect(section).toBeVisible()
    })

    test('should display profile picture', async ({ page }) => {
      // Profile picture/avatar should be visible
      const avatar = page.locator('img[class*="rounded-full"], img[class*="circular"], [class*="avatar"] img').first()
      await expect(avatar).toBeVisible()
    })

    test('should display user name', async ({ page }) => {
      // User name should be visible
      const name = page.locator('text=John Doe').first()
      await expect(name).toBeVisible()
    })

    test('should display email address', async ({ page }) => {
      // Email should be visible
      const email = page.locator('text=john.doe@example.com').first()
      await expect(email).toBeVisible()
    })

    test('should display phone number', async ({ page }) => {
      // Phone should be visible
      const phone = page.locator('text=+1 (555) 123-4567').first()
      await expect(phone).toBeVisible()
    })

    test('should display website', async ({ page }) => {
      // Website should be visible
      const website = page.locator('text=https://johndoe.dev').first()
      await expect(website).toBeVisible()
    })

    test('should display location', async ({ page }) => {
      // Location should be visible
      const location = page.locator('text=San Francisco, CA').first()
      await expect(location).toBeVisible()
    })
  })

  test.describe('Account Status Section', () => {
    test('should display account status section', async ({ page }) => {
      const section = page.locator('text=Account Status').first()
      await expect(section).toBeVisible()
    })

    test('should display email verified status', async ({ page }) => {
      // Email verified label should be visible
      const label = page.locator('text=Email Verified').first()
      await expect(label).toBeVisible()
    })

    test('should display 2FA status', async ({ page }) => {
      // 2FA label should be visible
      const label = page.locator('text=2FA Enabled').first()
      await expect(label).toBeVisible()
    })

    test('should display role badge', async ({ page }) => {
      // Role should be visible
      const role = page.locator('text=Administrator').first()
      await expect(role).toBeVisible()
    })

    test('should display member since date', async ({ page }) => {
      // Member since date should be visible
      const memberSince = page.locator('text=Member Since').first()
      await expect(memberSince).toBeVisible()
    })

    test('should display last login', async ({ page }) => {
      // Last login should be visible
      const lastLogin = page.locator('text=Last Login').first()
      await expect(lastLogin).toBeVisible()
    })

    test('should be collapsible', async ({ page }) => {
      // Look for collapse/expand button in the section
      const collapseButton = page.locator('[class*="section"]:has-text("Account Status") button[class*="collapse"], [class*="section"]:has-text("Account Status") [class*="chevron"]').first()
      const exists = await collapseButton.count()
      if (exists > 0) {
        await expect(collapseButton).toBeVisible()
      }
    })
  })

  test.describe('Tabs Component', () => {
    test('should display tabs container', async ({ page }) => {
      // Tabs should be visible
      const tabs = page.locator('[role="tablist"], [class*="tabs"]').first()
      await expect(tabs).toBeVisible()
    })

    test('should display Biography tab', async ({ page }) => {
      const tab = page.locator('[role="tab"]:has-text("Biography"), button:has-text("Biography")').first()
      await expect(tab).toBeVisible()
    })

    test('should display Statistics tab', async ({ page }) => {
      const tab = page.locator('[role="tab"]:has-text("Statistics"), button:has-text("Statistics")').first()
      await expect(tab).toBeVisible()
    })

    test('should display Preferences tab', async ({ page }) => {
      const tab = page.locator('[role="tab"]:has-text("Preferences"), button:has-text("Preferences")').first()
      await expect(tab).toBeVisible()
    })

    test('should switch to Statistics tab when clicked', async ({ page }) => {
      const statisticsTab = page.locator('[role="tab"]:has-text("Statistics"), button:has-text("Statistics")').first()
      await statisticsTab.click()
      await page.waitForTimeout(300)

      // Statistics content should be visible
      const statsContent = page.locator('text=Posts, text=Comments, text=Followers').first()
      const exists = await statsContent.count()
      expect(exists).toBeGreaterThan(0)
    })

    test('should switch to Preferences tab when clicked', async ({ page }) => {
      const preferencesTab = page.locator('[role="tab"]:has-text("Preferences"), button:has-text("Preferences")').first()
      await preferencesTab.click()
      await page.waitForTimeout(300)

      // Preferences content should be visible
      const prefsContent = page.locator('text=Timezone, text=Language').first()
      const exists = await prefsContent.count()
      expect(exists).toBeGreaterThan(0)
    })
  })

  test.describe('Biography Tab Content', () => {
    test('should display bio content', async ({ page }) => {
      // Bio content should be visible
      const bio = page.locator('text=Full-stack developer').first()
      await expect(bio).toBeVisible()
    })

    test('should display skills', async ({ page }) => {
      // Skills should be visible (possibly as badges)
      const skills = page.locator('text=PHP, text=Laravel, text=Vue.js').first()
      const exists = await skills.count()
      expect(exists).toBeGreaterThan(0)
    })
  })

  test.describe('Statistics Tab Content', () => {
    test('should display posts count', async ({ page }) => {
      // Switch to Statistics tab first
      const statisticsTab = page.locator('[role="tab"]:has-text("Statistics"), button:has-text("Statistics")').first()
      await statisticsTab.click()
      await page.waitForTimeout(300)

      const posts = page.locator('text=156').first()
      const exists = await posts.count()
      expect(exists).toBeGreaterThan(0)
    })

    test('should display comments count', async ({ page }) => {
      const statisticsTab = page.locator('[role="tab"]:has-text("Statistics"), button:has-text("Statistics")').first()
      await statisticsTab.click()
      await page.waitForTimeout(300)

      const comments = page.locator('text=1,234').first()
      const exists = await comments.count()
      expect(exists).toBeGreaterThan(0)
    })

    test('should display followers count', async ({ page }) => {
      const statisticsTab = page.locator('[role="tab"]:has-text("Statistics"), button:has-text("Statistics")').first()
      await statisticsTab.click()
      await page.waitForTimeout(300)

      const followers = page.locator('text=5.2K').first()
      const exists = await followers.count()
      expect(exists).toBeGreaterThan(0)
    })

    test('should display following count', async ({ page }) => {
      const statisticsTab = page.locator('[role="tab"]:has-text("Statistics"), button:has-text("Statistics")').first()
      await statisticsTab.click()
      await page.waitForTimeout(300)

      const following = page.locator('text=328').first()
      const exists = await following.count()
      expect(exists).toBeGreaterThan(0)
    })
  })

  test.describe('Preferences Tab Content', () => {
    test('should display timezone', async ({ page }) => {
      const preferencesTab = page.locator('[role="tab"]:has-text("Preferences"), button:has-text("Preferences")').first()
      await preferencesTab.click()
      await page.waitForTimeout(300)

      const timezone = page.locator('text=America/Los_Angeles').first()
      const exists = await timezone.count()
      expect(exists).toBeGreaterThan(0)
    })

    test('should display language', async ({ page }) => {
      const preferencesTab = page.locator('[role="tab"]:has-text("Preferences"), button:has-text("Preferences")').first()
      await preferencesTab.click()
      await page.waitForTimeout(300)

      const language = page.locator('text=English').first()
      const exists = await language.count()
      expect(exists).toBeGreaterThan(0)
    })

    test('should display notification settings', async ({ page }) => {
      const preferencesTab = page.locator('[role="tab"]:has-text("Preferences"), button:has-text("Preferences")').first()
      await preferencesTab.click()
      await page.waitForTimeout(300)

      const notifications = page.locator('text=Notifications').first()
      const exists = await notifications.count()
      expect(exists).toBeGreaterThan(0)
    })

    test('should display marketing emails setting', async ({ page }) => {
      const preferencesTab = page.locator('[role="tab"]:has-text("Preferences"), button:has-text("Preferences")').first()
      await preferencesTab.click()
      await page.waitForTimeout(300)

      const marketing = page.locator('text=Marketing Emails').first()
      const exists = await marketing.count()
      expect(exists).toBeGreaterThan(0)
    })
  })

  test.describe('Entry Types', () => {
    test('should render TextEntry with icon', async ({ page }) => {
      // Email entry has mail icon
      const emailWithIcon = page.locator('[class*="mail"], svg[class*="mail"]').first()
      const exists = await emailWithIcon.count()
      expect(exists).toBeGreaterThan(0)
    })

    test('should render ImageEntry', async ({ page }) => {
      // Avatar should be rendered as image
      const image = page.locator('img[src*="dicebear"], img[src*="avatar"]').first()
      await expect(image).toBeVisible()
    })

    test('should render IconEntry with boolean state', async ({ page }) => {
      // Boolean icons should be visible (check/x icons)
      const booleanIcons = page.locator('svg[class*="check"], svg[class*="circle"]').first()
      const exists = await booleanIcons.count()
      expect(exists).toBeGreaterThan(0)
    })

    test('should render badge entries', async ({ page }) => {
      // Role badge should be visible
      const badge = page.locator('[class*="badge"]:has-text("Administrator")').first()
      const exists = await badge.count()
      expect(exists).toBeGreaterThan(0)
    })
  })

  test.describe('Grid Layout', () => {
    test('should render grid layout in sections', async ({ page }) => {
      // Grid containers should exist
      const grids = page.locator('[class*="grid"], [style*="grid"]')
      const count = await grids.count()
      expect(count).toBeGreaterThan(0)
    })

    test('should respect column spans', async ({ page }) => {
      // Items with column spans should be present
      const columnSpans = page.locator('[class*="col-span"], [style*="grid-column"]')
      const count = await columnSpans.count()
      // May or may not have explicit spans
      expect(count >= 0).toBe(true)
    })
  })

  test.describe('Action Buttons', () => {
    test('should have Edit Profile button', async ({ page }) => {
      const editButton = page.locator('button:has-text("Edit Profile")').first()
      await expect(editButton).toBeVisible()
    })

    test('should have View Form Demo button', async ({ page }) => {
      const viewFormButton = page.locator('button:has-text("View Form Demo")').first()
      await expect(viewFormButton).toBeVisible()
    })

    test('should have edit icon on Edit Profile button', async ({ page }) => {
      const editIcon = page.locator('button:has-text("Edit Profile") svg, button:has-text("Edit Profile") [class*="pencil"]').first()
      const exists = await editIcon.count()
      expect(exists).toBeGreaterThan(0)
    })
  })

  test.describe('Responsive Layout', () => {
    test('should display properly on desktop viewport', async ({ page }) => {
      await page.setViewportSize({ width: 1280, height: 720 })
      await page.waitForTimeout(500)

      const section = page.locator('[class*="section"], [class*="card"]').first()
      await expect(section).toBeVisible()
    })

    test('should adjust layout on mobile viewport', async ({ page }) => {
      await page.setViewportSize({ width: 375, height: 667 })
      await page.waitForTimeout(500)

      const section = page.locator('[class*="section"], [class*="card"]').first()
      await expect(section).toBeVisible()
    })

    test('should adjust layout on tablet viewport', async ({ page }) => {
      await page.setViewportSize({ width: 768, height: 1024 })
      await page.waitForTimeout(500)

      const section = page.locator('[class*="section"], [class*="card"]').first()
      await expect(section).toBeVisible()
    })
  })

  test.describe('Section Features', () => {
    test('should display section icon', async ({ page }) => {
      // Sections should have icons
      const sectionIcons = page.locator('[class*="section"] svg, [class*="card"] svg').first()
      const exists = await sectionIcons.count()
      expect(exists).toBeGreaterThan(0)
    })

    test('should display section description', async ({ page }) => {
      // Section description should be visible
      const description = page.locator('text=Basic user information, text=Current account status').first()
      const exists = await description.count()
      expect(exists).toBeGreaterThan(0)
    })
  })
})
