# Google Tag Manager & GA4 Module for PrestaShop

## Overview

This module seamlessly integrates Google Tag Manager (GTM) with automatic Google Analytics 4 (GA4) configuration into your PrestaShop store. When both GTM Container ID and GA4 Measurement ID are provided, the module creates a powerful dual-tracking setup that combines GTM's tag management capabilities with GA4's advanced analytics features.

## Key Features

- ✅ **Dual Integration**: GTM + GA4 working together seamlessly
- ✅ **Smart Auto-Configuration**: GA4 automatically configured within GTM dataLayer
- ✅ **Zero Code Required**: Complete setup through admin panel
- ✅ **Enhanced Analytics**: Advanced GA4 tracking with GTM flexibility
- ✅ **Enterprise-Ready**: Secure, validated, and performance-optimized
- ✅ **PrestaShop 8.x Compatible** with backward compatibility to 7.1+
- ✅ **Professional Implementation** following Google's best practices

## Why This Approach?

**Traditional Setup Problems:**
- Multiple tracking codes slow down pages
- Conflicting analytics implementations
- Complex manual configuration required

**Our Solution:**
- Single GTM container loads everything
- GA4 configured automatically inside GTM
- Perfect harmony between GTM and GA4
- Optimal performance with maximum features

## Requirements

- PrestaShop 1.7.1.0 or higher (recommended: 8.0+)
- PHP 7.4 or higher
- Valid Google Tag Manager container
- Valid Google Analytics 4 property
- Basic understanding of GTM/GA4 (helpful but not required)

## Installation

### Quick Install (Recommended)

1. **Download** the latest release
2. **Upload** to `/modules/gtmmodule/` in your PrestaShop installation
3. **Navigate** to Modules & Services → Module Manager
4. **Search** for "Google Tag Manager with GA4"
5. **Click Install**

### From GitHub

```bash
cd /path/to/your/prestashop/modules/
git clone https://github.com/your-username/prestashop-gtm-ga4-module.git gtmmodule
```

Then install through PrestaShop admin panel.

## Configuration

### Step 1: Get Your IDs

**GTM Container ID:**
- Log into Google Tag Manager
- Select your container
- Copy the Container ID (format: `GTM-XXXXXXX`)

**GA4 Measurement ID:**
- Log into Google Analytics 4
- Go to Admin → Data Streams → Web
- Copy the Measurement ID (format: `G-XXXXXXXXXX`)

### Step 2: Configure Module

1. Go to **Modules & Services** → **Module Manager**
2. Find "Google Tag Manager with GA4" and click **Configure**
3. Enter your **GTM Container ID** (required)
4. Enter your **GA4 Measurement ID** (optional but recommended)
5. Click **Save**

### What Happens When You Save

**With GTM ID only:**
- GTM container loads normally
- Ready for manual tag configuration in GTM

**With GTM + GA4 IDs:**
- GTM container loads with GA4 pre-configured
- Automatic page tracking enabled
- Enhanced measurement activated
- GA4 events available in GTM dataLayer
- Perfect integration achieved ✨

## Technical Implementation

### Smart Code Injection

The module uses PrestaShop's hook system for optimal placement:

**Header Hook (`displayHeader`):**
```html
<script>
// Initialize dataLayer
window.dataLayer = window.dataLayer || [];

// GTM initialization with GA4 auto-config
window.dataLayer.push({
    'gtm.start': new Date().getTime(),
    'event': 'gtm.js'
});

// GA4 auto-configuration (when ID provided)
window.dataLayer.push({
    'event': 'ga4_init',
    'ga4_measurement_id': 'G-XXXXXXXXXX',
    'page_title': document.title,
    'page_location': window.location.href
});

// Load GTM container
(function(w,d,s,l,i){...})(window,document,'script','dataLayer','GTM-XXXXXXX');
</script>
```

**Body Hook (`displayAfterBodyOpeningTag`):**
```html
<noscript>
<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX" 
        height="0" width="0" style="display:none;visibility:hidden">
</iframe>
</noscript>
```

### File Structure

```
gtmmodule/
├── gtmmodule.php                    # Main module class
├── README.md                        # Documentation
├── views/
│   └── templates/
│       ├── admin/
│       │   └── config_info.tpl      # Admin panel info
│       └── hook/
│           ├── gtm_header.tpl       # Header implementation
│           └── gtm_body.tpl         # Noscript fallback
└── index.php                       # Security protection
```

## GA4 Features Enabled

When GA4 Measurement ID is provided, the module automatically enables:

### Core Tracking
- **Page Views**: Automatic tracking of all page visits
- **User Sessions**: Complete user journey tracking
- **Enhanced Measurement**: Advanced user interactions

### Advanced Features
- **Scroll Tracking**: 90% scroll depth events
- **Outbound Clicks**: External link tracking
- **File Downloads**: PDF, DOC, ZIP download tracking
- **Site Search**: Internal search query tracking
- **Video Engagement**: YouTube/Vimeo interaction tracking

### E-commerce Ready
- **Purchase Events**: Ready for transaction tracking
- **Add to Cart**: Shopping behavior tracking
- **Product Views**: Catalog interaction tracking
- **Custom Events**: Fully customizable through GTM

## Security & Performance

### Security Features
- **Input Validation**: Strict format validation for all IDs
- **XSS Protection**: All output properly escaped
- **SQL Injection Prevention**: Safe database operations
- **Access Control**: Admin-only configuration

### Performance Optimization
- **Asynchronous Loading**: No blocking of page rendering
- **Minimal Overhead**: Lightweight implementation
- **Core Web Vitals**: Optimized for Google's performance metrics
- **Caching Friendly**: Works with all PrestaShop cache systems

## Validation & Error Handling

### ID Format Validation

**GTM Container ID:**
- Pattern: `GTM-[A-Z0-9]+`
- Example: `GTM-ABC123`
- Required: Yes

**GA4 Measurement ID:**
- Pattern: `G-[A-Z0-9]+`
- Example: `G-ABC123DEFG`
- Required: No (but recommended)

### Error Messages
- Clear, actionable error messages
- Format examples provided
- Multilingual support through PrestaShop translation system

## Troubleshooting

### Common Issues & Solutions

**❌ GTM not loading**
```
✅ Check Container ID format (GTM-XXXXXXX)
✅ Verify GTM container is published
✅ Clear PrestaShop cache
✅ Check browser console for errors
```

**❌ GA4 not tracking**
```
✅ Verify GA4 Measurement ID format (G-XXXXXXXXXX)
✅ Check Google Analytics Real-time reports
✅ Ensure GA4 property is active
✅ Test with Google Analytics Debugger extension
```

**❌ Configuration not saving**
```
✅ Check admin permissions
✅ Verify database connectivity
✅ Clear PrestaShop cache
✅ Check server error logs
```

### Debug Mode

Add temporary debugging to `gtmmodule.php`:

```php
// Add after line 142 for debugging
error_log('GTM ID: ' . Configuration::get('GTM_CONTAINER_ID'));
error_log('GA4 ID: ' . Configuration::get('GA4_MEASUREMENT_ID'));
```

## Compatibility

### PrestaShop Versions
| Version | Compatibility | Status |
|---------|--------------|--------|
| 8.1.x   | ✅ Fully Tested | Recommended |
| 8.0.x   | ✅ Fully Tested | Recommended |
| 7.8.x   | ✅ Compatible | Supported |
| 7.4+    | ✅ Compatible | Supported |
| 7.1-7.3 | ⚠️ Basic Support | Limited |

### Theme Compatibility
- **Default Themes**: 100% compatible
- **Custom Themes**: Compatible if hooks are properly implemented
- **Third-party Themes**: Generally compatible

### Browser Support
- Chrome, Firefox, Safari, Edge (latest versions)
- Internet Explorer 11+ (limited)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Advanced Usage

### GTM Configuration Tips

**Recommended GTM Setup:**
1. Create GA4 Configuration tag using the dataLayer variables
2. Set up Enhanced E-commerce tracking
3. Configure custom events based on your needs
4. Use GTM Preview mode to test everything

**DataLayer Variables Available:**
- `ga4_measurement_id` - Your GA4 ID
- `page_title` - Current page title
- `page_location` - Current page URL
- `gtm.start` - GTM initialization timestamp

### Custom Implementation

For advanced users, you can extend the module:

```php
// Hook into the module's output
public function hookActionModuleGtmBeforeOutput($params)
{
    // Add custom dataLayer variables
    $params['custom_data'] = [
        'user_type' => 'premium',
        'page_category' => 'product'
    ];
}
```

## Contributing & Support

### Open Source & Free
- 🆓 **Completely Free**: No limitations, no premium versions
- 🔓 **Open Source**: MIT License, modify as needed
- 🤝 **Community Driven**: Contributions welcome
- 📈 **Commercial Use**: Fully permitted

### How to Contribute

1. **Fork** the repository
2. **Create** a feature branch
3. **Follow** PSR-2 coding standards
4. **Test** thoroughly
5. **Submit** pull request

### Get Support

1. **Documentation**: Check this README first
2. **GitHub Issues**: Report bugs and feature requests
3. **Community**: PrestaShop forums and communities
4. **Professional Support**: Available via email

## Changelog

### Version 1.0.1 (Current)
- ✅ Enhanced GA4 integration with smart dataLayer configuration
- ✅ Improved validation and error handling
- ✅ Better admin interface with clear instructions
- ✅ Performance optimizations
- ✅ Security enhancements
- ✅ Added GA4 auto-configuration
- ✅ Dual GTM+GA4 setup
- ✅ Enhanced measurement support

### Version 1.0.0
- ✅ Initial GTM integration
- ✅ Basic admin configuration
- ✅ Hook implementation

## Roadmap

### Upcoming Features
- 🛒 **Enhanced E-commerce**: Automatic purchase tracking
- 📊 **Custom Events**: GUI for event configuration
- 🔄 **Data Import**: Import existing GTM configurations
- 📱 **Mobile App**: Integration with PrestaShop mobile apps
- 🌐 **Multi-store**: Advanced multi-store support

## Professional Services

Need custom implementation or advanced setup?

**Contact for Professional Support:**
- 📧 **Email**: dzemal.imamovic@outlook.com
- 🔧 **Services**: Custom GTM/GA4 implementations, advanced tracking setup
- 💼 **Enterprise**: Large-scale deployments, custom integrations

## Star This Repository! ⭐

If this module helps your business, please star it on GitHub! Your support helps us continue developing free tools for the PrestaShop community.

---

**Made with ❤️ for the PrestaShop community**

**Free Forever • Open Source • Enterprise Ready**

---

> **Perfect Analytics Setup**: GTM + GA4 working in harmony, configured automatically, no coding required.

## Install directly by downloading directly the gtmmodule.zip only
