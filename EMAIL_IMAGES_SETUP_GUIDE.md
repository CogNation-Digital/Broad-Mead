# 🚀 Email Footer Images Setup Guide

## 📋 **Current Status:**
- ✅ Email footer code updated to use `https://broad-mead.com/images/` URLs
- ✅ Both candidates and mailshot systems updated
- ⚠️ **Next Step**: Upload your images to the correct location

## 🎯 **Step-by-Step Solution:**

### **Step 1: Convert Google Drive Links to Direct URLs** 
Google Drive links like `https://drive.google.com/file/d/1SR3INbhT1SC0CXmKG-1EFRlSOe3QDULC/view?usp=drive_link` don't work in emails.

**Convert to direct link:**
```
Original: https://drive.google.com/file/d/1SR3INbhT1SC0CXmKG-1EFRlSOe3QDULC/view?usp=drive_link
Direct:   https://drive.google.com/uc?export=view&id=1SR3INbhT1SC0CXmKG-1EFRlSOe3QDULC
```

### **Step 2: Upload Images to Your Live Server** ⭐ **RECOMMENDED**

**Upload these files to `https://broad-mead.com/images/` folder:**

1. **`image001.jpg`** - Main Nocturnal Recruitment logo
2. **`Linked in badge.jpg`** - LinkedIn social media badge  
3. **`Facebook logo.jpg`** - Facebook social media icon
4. **`image012.jpg`** - Instagram badge
5. **`image008.png`** - Cyber Essentials certification badge
6. **`image009.jpg`** - REC Corporate Member badge

**Via FTP/cPanel File Manager:**
```
/public_html/images/image001.jpg
/public_html/images/Linked in badge.jpg
/public_html/images/Facebook logo.jpg
/public_html/images/image012.jpg
/public_html/images/image008.png
/public_html/images/image009.jpg
```

### **Step 3: Test Image URLs**

After uploading, test each image URL in your browser:

✅ **Main Logo**: https://broad-mead.com/images/image001.jpg
✅ **LinkedIn**: https://broad-mead.com/images/Linked%20in%20badge.jpg
✅ **Facebook**: https://broad-mead.com/images/Facebook%20logo.jpg
✅ **Instagram**: https://broad-mead.com/images/image012.jpg
✅ **Cyber Essentials**: https://broad-mead.com/images/image008.png
✅ **REC Corporate**: https://broad-mead.com/images/image009.jpg

### **Step 4: Upload Updated PHP Files**

Upload these updated files to your live server:
- ✅ `src/candidates/index.php` (updated with absolute URLs)
- ✅ `mailshot_functionality.php` (updated with absolute URLs)

---

## 🔄 **Alternative Options** (if Step 2 doesn't work):

### **Option A: Use Direct Google Drive URLs**
If you prefer to keep images on Google Drive, I can update the code to use direct Google Drive URLs:

```php
// Direct Google Drive URLs (replace FILE_ID with your actual IDs)
$emailBaseUrl = 'https://drive.google.com/uc?export=view&id=';
$imageUrls = [
    'logo' => $emailBaseUrl . '1SR3INbhT1SC0CXmKG-1EFRlSOe3QDULC',
    'linkedin' => $emailBaseUrl . 'YOUR_LINKEDIN_FILE_ID',
    'facebook' => $emailBaseUrl . 'YOUR_FACEBOOK_FILE_ID',
    // ... etc
];
```

### **Option B: Use Image Hosting Service**
- Upload to **ImgBB**, **Imgur**, or **Cloudinary**
- Get direct image URLs
- Update the email footer code

### **Option C: Embed Images in Email**
- Convert images to base64
- Embed directly in email HTML
- **Pros**: Always works
- **Cons**: Larger email size

---

## 🧪 **Testing Your Setup:**

### **Test File Available:**
Visit: `https://broad-mead.com/test_email_footer.php`

This will show:
- ✅ Preview of email footer
- ✅ Image loading status
- ✅ Visual verification of all badges

### **Real Email Test:**
1. Send yourself a test mailshot
2. Check email in Gmail/Outlook
3. Verify all images display correctly

---

## 📞 **Need Help?**

If you have trouble with any step:

1. **Share your Google Drive file IDs** for all images
2. **Confirm your hosting setup** (cPanel, FTP access, etc.)
3. **Let me know which option** you prefer (server upload vs Google Drive)

The current code is ready to work with either approach!
