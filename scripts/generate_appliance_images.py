"""
Generate clean, professional e-commerce product images on a pure white background
for all 35 home appliance subcategories in NegarThem.
"""

import os
from PIL import Image, ImageDraw

OUTPUT_DIR = r"X:\apps\wamp\www\wp4\wp-content\uploads\appliances\products"
os.makedirs(OUTPUT_DIR, exist_ok=True)

SIZE = 1200  # Supersampled size
FINAL_SIZE = 800  # Downscaled for antialiasing

def create_base():
    img = Image.new("RGB", (SIZE, SIZE), "#FFFFFF")
    draw = ImageDraw.Draw(img)
    return img, draw

def add_drop_shadow(draw, cx, cy, rx, ry, opacity=80):
    # Elliptical contact drop shadow
    for i in range(15):
        alpha = int(opacity * (1 - i / 15.0))
        r_step = i * 4
        bbox = [cx - rx - r_step, cy - ry - r_step // 2, cx + rx + r_step, cy + ry + r_step // 2]
        color = (230 - alpha // 3, 230 - alpha // 3, 230 - alpha // 3)
        draw.ellipse(bbox, fill=color)

def finalize_and_save(img, slug):
    res = img.resize((FINAL_SIZE, FINAL_SIZE), Image.Resampling.LANCZOS)
    path = os.path.join(OUTPUT_DIR, f"{slug}.png")
    res.save(path, "PNG", optimize=True)
    print(f"Generated: {slug}.png")
    return path

# Category 1: Major Appliances
def draw_refrigerators_freezers():
    img, d = create_base()
    add_drop_shadow(d, 600, 1020, 260, 40)
    # Side by side fridge
    d.rounded_rectangle([360, 200, 840, 1000], radius=24, fill="#D1D5DB", outline="#9CA3AF", width=4)
    # Left door (Freezer)
    d.rounded_rectangle([370, 210, 580, 990], radius=14, fill="#E5E7EB", outline="#9CA3AF", width=3)
    # Right door (Fridge)
    d.rounded_rectangle([590, 210, 830, 990], radius=14, fill="#E5E7EB", outline="#9CA3AF", width=3)
    # Water/ice dispenser on left door
    d.rounded_rectangle([410, 440, 540, 640], radius=10, fill="#1F2937", outline="#374151", width=3)
    d.rounded_rectangle([430, 460, 520, 510], radius=4, fill="#0284C7")
    d.rounded_rectangle([460, 540, 490, 600], radius=4, fill="#6B7280")
    # Digital temperature display on right door
    d.rounded_rectangle([660, 300, 760, 360], radius=8, fill="#111827")
    d.rectangle([680, 325, 740, 335], fill="#10B981")
    # Handles
    d.rounded_rectangle([560, 400, 576, 750], radius=6, fill="#9CA3AF", outline="#6B7280", width=2)
    d.rounded_rectangle([594, 400, 610, 750], radius=6, fill="#9CA3AF", outline="#6B7280", width=2)
    return finalize_and_save(img, "refrigerators-freezers")

def draw_washing_machines():
    img, d = create_base()
    add_drop_shadow(d, 600, 1010, 270, 40)
    # Main body
    d.rounded_rectangle([350, 240, 850, 1000], radius=24, fill="#F3F4F6", outline="#D1D5DB", width=4)
    # Top control panel
    d.rectangle([352, 242, 848, 380], fill="#FFFFFF", outline="#E5E7EB", width=2)
    # Detergent drawer
    d.rounded_rectangle([380, 270, 500, 350], radius=8, fill="#F9FAFB", outline="#D1D5DB", width=2)
    # Rotary dial knob
    d.ellipse([570, 270, 630, 330], fill="#D1D5DB", outline="#9CA3AF", width=3)
    d.ellipse([585, 285, 615, 315], fill="#111827")
    # Touch screen
    d.rounded_rectangle([680, 270, 810, 340], radius=6, fill="#1F2937")
    d.rectangle([700, 295, 790, 315], fill="#0284C7")
    # Front Porthole Door
    d.ellipse([420, 460, 780, 820], fill="#374151", outline="#9CA3AF", width=12)
    d.ellipse([460, 500, 740, 780], fill="#111827", outline="#4B5563", width=6)
    d.ellipse([490, 530, 710, 750], fill="#60A5FA", outline="#93C5FD", width=4)
    # Door handle
    d.arc([430, 470, 770, 810], start=330, end=30, fill="#FFFFFF", width=8)
    return finalize_and_save(img, "washing-machines")

def draw_dishwashers():
    img, d = create_base()
    add_drop_shadow(d, 600, 1010, 260, 38)
    d.rounded_rectangle([360, 240, 840, 1000], radius=20, fill="#F9FAFB", outline="#D1D5DB", width=4)
    # Top integrated control strip
    d.rectangle([362, 242, 838, 330], fill="#1F2937")
    d.rounded_rectangle([530, 265, 670, 305], radius=4, fill="#0284C7")
    for x in [400, 440, 480, 720, 760, 800]:
        d.ellipse([x, 275, x + 20, 295], fill="#6B7280")
    # Recessed handle
    d.rounded_rectangle([480, 350, 720, 380], radius=8, fill="#E5E7EB", outline="#9CA3AF", width=2)
    # Clean front stainless door
    d.rounded_rectangle([370, 390, 830, 940], radius=8, fill="#FFFFFF", outline="#E5E7EB", width=2)
    # Bottom toe-kick plinth
    d.rectangle([362, 940, 838, 998], fill="#374151")
    return finalize_and_save(img, "dishwashers")

def draw_cookers_stoves():
    img, d = create_base()
    add_drop_shadow(d, 600, 1010, 280, 42)
    # Body
    d.rounded_rectangle([340, 300, 860, 1000], radius=16, fill="#E5E7EB", outline="#9CA3AF", width=4)
    # Cooktop burners on top
    d.rectangle([330, 280, 870, 330], fill="#9CA3AF", outline="#6B7280", width=3)
    for bx in [400, 520, 600, 680, 800]:
        d.ellipse([bx - 25, 290, bx + 25, 320], fill="#1F2937")
    # Control knobs strip
    d.rectangle([342, 330, 858, 410], fill="#F3F4F6", outline="#D1D5DB", width=2)
    for kx in [380, 450, 520, 680, 750, 820]:
        d.ellipse([kx - 18, 350, kx + 18, 390], fill="#4B5563", outline="#1F2937", width=2)
    # Center clock / timer
    d.rounded_rectangle([570, 350, 630, 390], radius=4, fill="#111827")
    d.rectangle([580, 365, 620, 375], fill="#10B981")
    # Oven glass door
    d.rounded_rectangle([370, 440, 830, 880], radius=12, fill="#1F2937", outline="#4B5563", width=4)
    d.rounded_rectangle([420, 490, 780, 830], fill="#374151")
    d.rounded_rectangle([400, 455, 800, 480], radius=6, fill="#D1D5DB", outline="#9CA3AF", width=2)
    # Bottom warming drawer
    d.rounded_rectangle([370, 900, 830, 970], radius=8, fill="#E5E7EB", outline="#9CA3AF", width=2)
    return finalize_and_save(img, "cookers-stoves")

def draw_built_in_ovens():
    img, d = create_base()
    add_drop_shadow(d, 600, 960, 260, 35)
    # Oven outer frame
    d.rounded_rectangle([340, 240, 860, 940], radius=14, fill="#111827", outline="#374151", width=5)
    # Top control panel
    d.rectangle([344, 244, 856, 380], fill="#1F2937")
    d.ellipse([400, 285, 460, 345], fill="#9CA3AF", outline="#4B5563", width=3)
    d.ellipse([740, 285, 800, 345], fill="#9CA3AF", outline="#4B5563", width=3)
    # Center digital touch screen
    d.rounded_rectangle([520, 280, 680, 345], radius=8, fill="#0F172A", outline="#0284C7", width=2)
    d.rectangle([550, 305, 650, 320], fill="#38BDF8")
    # Sleek bar handle
    d.rounded_rectangle([380, 410, 820, 435], radius=6, fill="#E5E7EB", outline="#9CA3AF", width=2)
    # Triple-glazed dark glass door
    d.rounded_rectangle([360, 450, 840, 910], radius=10, fill="#1E293B", outline="#475569", width=4)
    # Oven interior viewing window
    d.rounded_rectangle([420, 510, 780, 850], radius=8, fill="#0F172A")
    # Internal wire rack glow
    for y in [600, 680, 760]:
        d.line([440, y, 760, y], fill="#F59E0B", width=4)
    return finalize_and_save(img, "built-in-ovens")

def draw_range_hoods():
    img, d = create_base()
    add_drop_shadow(d, 600, 860, 280, 30)
    # Upper chimney duct
    d.rectangle([480, 200, 720, 480], fill="#D1D5DB", outline="#9CA3AF", width=3)
    # Slanted modern canopy
    d.polygon([(300, 780), (900, 780), (840, 480), (360, 480)], fill="#1F2937", outline="#374151")
    # Tempered glass front panel
    d.polygon([(280, 820), (920, 820), (880, 520), (320, 520)], fill="#111827", outline="#000000")
    # Touch control bar & LED lighting strip
    d.rectangle([440, 640, 760, 675], fill="#374151")
    for tx in [480, 540, 600, 660, 720]:
        d.ellipse([tx, 650, tx + 14, 665], fill="#38BDF8")
    # Bottom LED light bar
    d.rounded_rectangle([360, 800, 840, 815], radius=4, fill="#FEF08A")
    return finalize_and_save(img, "range-hoods")

# Category 2: Electric Cooking
def draw_microwaves():
    img, d = create_base()
    add_drop_shadow(d, 600, 860, 270, 35)
    # Microwave housing
    d.rounded_rectangle([320, 360, 880, 840], radius=18, fill="#E5E7EB", outline="#9CA3AF", width=4)
    # Door frame
    d.rounded_rectangle([340, 380, 720, 820], radius=12, fill="#1F2937", outline="#4B5563", width=3)
    # Tinted glass window
    d.rounded_rectangle([370, 420, 690, 780], radius=8, fill="#111827")
    d.rounded_rectangle([390, 440, 670, 760], radius=4, fill="#1E293B")
    # Right-side control keypad & display
    d.rounded_rectangle([735, 380, 860, 820], radius=8, fill="#F3F4F6", outline="#D1D5DB", width=2)
    # LED display
    d.rounded_rectangle([745, 400, 850, 460], radius=4, fill="#0F172A")
    d.rectangle([765, 420, 830, 440], fill="#10B981")
    # Dials / buttons
    for y in [490, 540, 590]:
        d.ellipse([770, y, 820, y + 35], fill="#9CA3AF", outline="#6B7280", width=2)
    # Handle / Door release
    d.rounded_rectangle([750, 680, 845, 780], radius=8, fill="#E5E7EB", outline="#9CA3AF", width=2)
    return finalize_and_save(img, "microwaves")

def draw_air_fryers():
    img, d = create_base()
    add_drop_shadow(d, 600, 940, 220, 35)
    # Rounded sleek body
    d.rounded_rectangle([380, 280, 820, 920], radius=48, fill="#1F2937", outline="#111827", width=4)
    # Top slanted digital touch display
    d.rounded_rectangle([430, 320, 770, 480], radius=24, fill="#0F172A", outline="#374151", width=2)
    d.ellipse([570, 370, 630, 430], fill="#38BDF8")
    for bx in [470, 520, 680, 730]:
        d.ellipse([bx - 12, 385, bx + 12, 415], fill="#6B7280")
    # Basket pull-out section
    d.rounded_rectangle([400, 520, 800, 880], radius=28, fill="#374151", outline="#4B5563", width=3)
    # Viewing glass window
    d.rounded_rectangle([450, 560, 750, 720], radius=16, fill="#0F172A", outline="#F59E0B", width=2)
    # Ergonomic front handle
    d.rounded_rectangle([560, 680, 640, 850], radius=14, fill="#111827", outline="#9CA3AF", width=3)
    return finalize_and_save(img, "air-fryers")

def draw_toaster_ovens():
    img, d = create_base()
    add_drop_shadow(d, 600, 880, 270, 35)
    # Body
    d.rounded_rectangle([320, 380, 880, 860], radius=16, fill="#D1D5DB", outline="#9CA3AF", width=4)
    # Glass drop-down door
    d.rounded_rectangle([340, 400, 720, 830], radius=10, fill="#1E293B", outline="#475569", width=3)
    d.rounded_rectangle([370, 440, 690, 790], radius=6, fill="#0F172A")
    # Interior rack with glowing elements
    d.line([380, 520, 680, 520], fill="#EF4444", width=5)
    d.line([380, 710, 680, 710], fill="#EF4444", width=5)
    # Door top bar handle
    d.rounded_rectangle([360, 415, 700, 435], radius=4, fill="#F3F4F6")
    # Right control knobs
    d.rectangle([735, 400, 860, 830], fill="#E5E7EB")
    for ky in [450, 560, 670]:
        d.ellipse([765, ky, 830, ky + 65], fill="#374151", outline="#1F2937", width=3)
    return finalize_and_save(img, "toaster-ovens")

def draw_rice_pressure_cookers():
    img, d = create_base()
    add_drop_shadow(d, 600, 940, 240, 36)
    # Main pot body
    d.rounded_rectangle([360, 420, 840, 920], radius=32, fill="#E5E7EB", outline="#9CA3AF", width=4)
    # Heavy lock lid
    d.rounded_rectangle([340, 320, 860, 430], radius=20, fill="#1F2937", outline="#111827", width=3)
    # Top pressure release handle & valve
    d.rounded_rectangle([520, 240, 680, 320], radius=16, fill="#374151", outline="#4B5563", width=2)
    d.ellipse([710, 270, 750, 310], fill="#9CA3AF")
    # Side carry handles
    d.rounded_rectangle([300, 500, 365, 600], radius=12, fill="#1F2937")
    d.rounded_rectangle([835, 500, 900, 600], radius=12, fill="#1F2937")
    # Front control display panel
    d.rounded_rectangle([440, 500, 760, 820], radius=18, fill="#111827", outline="#374151", width=3)
    d.rounded_rectangle([470, 530, 730, 630], radius=8, fill="#0F172A", outline="#0284C7", width=2)
    d.rectangle([520, 560, 680, 595], fill="#38BDF8")
    # Preset push buttons
    for by in [660, 720]:
        for bx in [480, 550, 620, 690]:
            d.rounded_rectangle([bx, by, bx + 45, by + 40], radius=6, fill="#374151")
    return finalize_and_save(img, "rice-pressure-cookers")

def draw_electric_grills_sandwich_makers():
    img, d = create_base()
    add_drop_shadow(d, 600, 900, 270, 35)
    # Bottom base
    d.rounded_rectangle([320, 580, 880, 870], radius=24, fill="#1F2937", outline="#111827", width=3)
    # Top hinged lid
    d.rounded_rectangle([330, 420, 870, 620], radius=20, fill="#E5E7EB", outline="#9CA3AF", width=4)
    # Large stay-cool front bar handle
    d.rounded_rectangle([310, 580, 890, 630], radius=12, fill="#374151", outline="#1F2937", width=3)
    # Indicator lights on lid
    d.ellipse([560, 470, 595, 505], fill="#EF4444")
    d.ellipse([605, 470, 640, 505], fill="#10B981")
    # Temperature rotary dial on base
    d.ellipse([740, 690, 820, 770], fill="#9CA3AF", outline="#4B5563", width=3)
    return finalize_and_save(img, "electric-grills-sandwich-makers")

# Category 3: Food Preparation
def draw_food_processors_blenders():
    img, d = create_base()
    add_drop_shadow(d, 600, 960, 240, 35)
    # Motor base unit
    d.rounded_rectangle([420, 600, 780, 940], radius=24, fill="#F3F4F6", outline="#D1D5DB", width=4)
    # Speed control dial
    d.ellipse([540, 720, 660, 840], fill="#374151", outline="#1F2937", width=4)
    d.ellipse([580, 760, 620, 800], fill="#0284C7")
    # Transparent large processing bowl
    d.rounded_rectangle([400, 300, 800, 610], radius=16, fill="#E0F2FE", outline="#7DD3FC", width=3)
    # Measuring markings on bowl
    for my in [380, 440, 500, 560]:
        d.line([420, my, 460, my], fill="#0284C7", width=3)
    # Center blade spindle
    d.rectangle([580, 360, 620, 590], fill="#9CA3AF")
    d.polygon([(520, 570), (680, 570), (600, 540)], fill="#64748B")
    # Top lid with feed chute & pusher
    d.rounded_rectangle([390, 270, 810, 320], radius=12, fill="#1F2937")
    d.rectangle([440, 160, 550, 280], fill="#BAE6FD", outline="#7DD3FC", width=2)
    d.rounded_rectangle([430, 130, 560, 170], radius=6, fill="#1F2937")
    return finalize_and_save(img, "food-processors-blenders")

def draw_juicers():
    img, d = create_base()
    add_drop_shadow(d, 600, 960, 230, 35)
    # Base
    d.rounded_rectangle([420, 540, 780, 940], radius=20, fill="#E5E7EB", outline="#9CA3AF", width=4)
    d.ellipse([560, 680, 640, 760], fill="#1F2937", outline="#4B5563", width=3)
    # Juice spout on side
    d.polygon([(420, 620), (340, 670), (340, 710), (420, 660)], fill="#9CA3AF", outline="#6B7280")
    # Transparent pulp container rear
    d.rounded_rectangle([680, 420, 820, 850], radius=16, fill="#FEF08A", outline="#FDE047", width=3)
    # Extraction chamber
    d.rounded_rectangle([440, 380, 760, 550], radius=14, fill="#E0F2FE", outline="#38BDF8", width=3)
    # Top feed tube
    d.rectangle([530, 180, 670, 390], fill="#9CA3AF", outline="#6B7280", width=3)
    d.rounded_rectangle([510, 140, 690, 190], radius=10, fill="#1F2937")
    # Safety lock arm
    d.arc([400, 340, 800, 700], start=160, end=380, fill="#374151", width=8)
    return finalize_and_save(img, "juicers")

def draw_meat_grinders():
    img, d = create_base()
    add_drop_shadow(d, 600, 940, 260, 35)
    # Motor body
    d.rounded_rectangle([460, 440, 840, 920], radius=24, fill="#F3F4F6", outline="#D1D5DB", width=4)
    # Power / Reverse switch
    d.rounded_rectangle([720, 560, 800, 660], radius=8, fill="#1F2937")
    d.ellipse([745, 580, 775, 610], fill="#EF4444")
    # Metal grinder head tube
    d.rectangle([320, 480, 480, 620], fill="#9CA3AF", outline="#6B7280", width=3)
    # Perforated extrusion plate front
    d.ellipse([270, 480, 340, 620], fill="#4B5563", outline="#1F2937", width=4)
    for hy in [510, 540, 570, 600]:
        for hx in [290, 310]:
            d.ellipse([hx, hy, hx + 8, hy + 8], fill="#D1D5DB")
    # Top metal feed tray & hopper
    d.rectangle([380, 320, 470, 490], fill="#9CA3AF", outline="#6B7280", width=3)
    d.rounded_rectangle([320, 240, 600, 330], radius=14, fill="#E5E7EB", outline="#9CA3AF", width=4)
    return finalize_and_save(img, "meat-grinders")

def draw_mixers():
    img, d = create_base()
    add_drop_shadow(d, 600, 950, 250, 35)
    # Heavy base plate
    d.rounded_rectangle([340, 840, 860, 930], radius=16, fill="#E5E7EB", outline="#9CA3AF", width=4)
    # Stand column
    d.polygon([(700, 420), (840, 420), (840, 860), (700, 860)], fill="#F9FAFB", outline="#D1D5DB")
    # Tilt motor head
    d.rounded_rectangle([380, 320, 850, 480], radius=28, fill="#FFFFFF", outline="#D1D5DB", width=4)
    # Speed control knob
    d.ellipse([740, 370, 800, 430], fill="#374151", outline="#1F2937", width=3)
    # Stainless mixing bowl
    d.polygon([(400, 520), (680, 520), (630, 850), (450, 850)], fill="#D1D5DB", outline="#9CA3AF")
    d.arc([400, 490, 680, 550], start=0, end=180, fill="#6B7280", width=4)
    # Beater attachment
    d.line([540, 460, 540, 720], fill="#9CA3AF", width=8)
    d.ellipse([500, 680, 580, 800], outline="#64748B", width=6)
    return finalize_and_save(img, "mixers")

def draw_choppers_grinders():
    img, d = create_base()
    add_drop_shadow(d, 600, 960, 220, 35)
    # Heavy glass bowl
    d.rounded_rectangle([380, 540, 820, 930], radius=24, fill="#F0F9FF", outline="#7DD3FC", width=4)
    # Inner multi-tiered stainless blades
    d.rectangle([585, 520, 615, 880], fill="#9CA3AF")
    d.polygon([(460, 680), (600, 680), (600, 650)], fill="#64748B")
    d.polygon([(600, 780), (740, 780), (600, 750)], fill="#64748B")
    # Intermediate lid
    d.rounded_rectangle([360, 490, 840, 550], radius=12, fill="#1F2937")
    # Top stainless press motor housing
    d.rounded_rectangle([440, 240, 760, 500], radius=20, fill="#E5E7EB", outline="#9CA3AF", width=4)
    # Top push button
    d.rounded_rectangle([480, 180, 720, 250], radius=14, fill="#111827", outline="#374151", width=3)
    return finalize_and_save(img, "choppers-grinders")

# Category 4: Beverage & Breakfast
def draw_tea_makers_electric_kettles():
    img, d = create_base()
    add_drop_shadow(d, 600, 960, 220, 35)
    # Base 360 ring
    d.rounded_rectangle([400, 890, 800, 940], radius=10, fill="#1F2937")
    # Lower electric glass kettle
    d.rounded_rectangle([430, 480, 770, 900], radius=20, fill="#E0F2FE", outline="#38BDF8", width=3)
    # Water level LED glow inside
    d.rounded_rectangle([440, 860, 760, 890], radius=6, fill="#0284C7")
    # Kettle handle
    d.arc([680, 520, 860, 860], start=270, end=90, fill="#1F2937", width=24)
    # Kettle spout
    d.polygon([(430, 540), (360, 480), (430, 500)], fill="#9CA3AF")
    # Stacked glass teapot on top
    d.rounded_rectangle([460, 260, 740, 480], radius=18, fill="#F0FDF4", outline="#86EFAC", width=3)
    d.arc([660, 300, 800, 460], start=270, end=90, fill="#1F2937", width=14)
    d.polygon([(460, 360), (400, 320), (460, 330)], fill="#9CA3AF")
    # Teapot lid with knob
    d.rounded_rectangle([510, 210, 690, 270], radius=8, fill="#1F2937")
    d.ellipse([580, 175, 620, 215], fill="#E5E7EB")
    return finalize_and_save(img, "tea-makers-electric-kettles")

def draw_coffee_espresso_machines():
    img, d = create_base()
    add_drop_shadow(d, 600, 960, 240, 35)
    # Espresso machine housing
    d.rounded_rectangle([380, 280, 820, 940], radius=20, fill="#E5E7EB", outline="#9CA3AF", width=4)
    # Top cup warming tray
    d.rectangle([390, 260, 810, 290], fill="#374151")
    # Circular pressure gauge
    d.ellipse([540, 340, 660, 460], fill="#FFFFFF", outline="#1F2937", width=4)
    d.ellipse([585, 385, 615, 415], fill="#EF4444")
    d.line([600, 400, 640, 370], fill="#111827", width=3)
    # Buttons
    for bx in [430, 480, 720, 770]:
        d.ellipse([bx - 16, 380, bx + 16, 420], fill="#4B5563")
    # Group head & portafilter handle
    d.rectangle([540, 500, 660, 560], fill="#1F2937")
    d.rounded_rectangle([420, 520, 550, 550], radius=8, fill="#111827")
    # Steam wand on right
    d.line([740, 520, 780, 680], fill="#9CA3AF", width=10)
    # Drip tray at bottom
    d.rounded_rectangle([390, 840, 810, 930], radius=12, fill="#374151")
    d.rectangle([410, 850, 790, 865], fill="#9CA3AF")
    return finalize_and_save(img, "coffee-espresso-machines")

def draw_coffee_grinders():
    img, d = create_base()
    add_drop_shadow(d, 600, 950, 200, 35)
    # Motor body
    d.rounded_rectangle([420, 520, 780, 920], radius=18, fill="#1F2937", outline="#111827", width=4)
    # Grind size selection dial
    d.ellipse([540, 680, 660, 800], fill="#E5E7EB", outline="#9CA3AF", width=4)
    d.ellipse([585, 725, 615, 755], fill="#0284C7")
    # Ground coffee collection box
    d.rounded_rectangle([440, 750, 760, 900], radius=10, fill="#374151", outline="#4B5563", width=2)
    # Upper bean hopper (tinted transparent)
    d.polygon([(460, 520), (740, 520), (780, 260), (420, 260)], fill="#FEF3C7", outline="#FCD34D", width=3)
    # Hopper lid
    d.rounded_rectangle([400, 210, 800, 270], radius=10, fill="#111827")
    return finalize_and_save(img, "coffee-grinders")

def draw_bread_toasters():
    img, d = create_base()
    add_drop_shadow(d, 600, 880, 260, 35)
    # Toaster body
    d.rounded_rectangle([340, 420, 860, 850], radius=36, fill="#F3F4F6", outline="#D1D5DB", width=4)
    # Dual bread slots on top
    d.rounded_rectangle([410, 390, 560, 435], radius=6, fill="#1F2937")
    d.rounded_rectangle([640, 390, 790, 435], radius=6, fill="#1F2937")
    # Browning control dial
    d.ellipse([550, 640, 650, 740], fill="#374151", outline="#1F2937", width=4)
    # Carriage press lever on side
    d.rectangle([840, 480, 880, 700], fill="#9CA3AF")
    d.rounded_rectangle([860, 490, 915, 540], radius=8, fill="#111827")
    # Crumb tray tab
    d.rectangle([360, 820, 430, 840], fill="#9CA3AF")
    return finalize_and_save(img, "bread-toasters")

def draw_water_dispensers():
    img, d = create_base()
    add_drop_shadow(d, 600, 1020, 210, 35)
    # Tall standing cabinet
    d.rounded_rectangle([400, 340, 800, 1000], radius=20, fill="#FFFFFF", outline="#D1D5DB", width=4)
    # Inverted large blue water bottle on top
    d.rounded_rectangle([460, 140, 740, 350], radius=24, fill="#38BDF8", outline="#0284C7", width=4)
    d.rectangle([550, 100, 650, 150], fill="#0284C7")
    # Dispensing alcove
    d.rounded_rectangle([440, 440, 760, 680], radius=14, fill="#F3F4F6", outline="#E5E7EB", width=2)
    # Hot tap (Red) & Cold tap (Blue)
    d.rounded_rectangle([490, 470, 540, 540], radius=6, fill="#EF4444")
    d.rounded_rectangle([660, 470, 710, 540], radius=6, fill="#0284C7")
    # Bottom drip cup tray
    d.rounded_rectangle([460, 640, 740, 675], radius=6, fill="#374151")
    # Lower storage door
    d.rounded_rectangle([430, 720, 770, 970], radius=10, fill="#F9FAFB", outline="#E5E7EB", width=2)
    return finalize_and_save(img, "water-dispensers")

# Category 5: Cleaning & Ironing
def draw_vacuum_cleaners():
    img, d = create_base()
    add_drop_shadow(d, 600, 920, 280, 35)
    # Canister vacuum aerodynamic body
    d.rounded_rectangle([380, 500, 820, 880], radius=40, fill="#EF4444", outline="#B91C1C", width=4)
    # Big rear wheels
    d.ellipse([340, 560, 460, 840], fill="#1F2937", outline="#374151", width=6)
    d.ellipse([740, 560, 860, 840], fill="#1F2937", outline="#374151", width=6)
    # Power & Cord rewind pedals
    d.rounded_rectangle([480, 740, 540, 830], radius=8, fill="#374151")
    d.rounded_rectangle([660, 740, 720, 830], radius=8, fill="#374151")
    # Suction power slider
    d.rounded_rectangle([570, 640, 630, 780], radius=6, fill="#111827")
    d.rectangle([585, 680, 615, 710], fill="#FBBF24")
    # Flexible hose inlet
    d.ellipse([550, 480, 650, 540], fill="#111827")
    return finalize_and_save(img, "vacuum-cleaners")

def draw_cordless_robot_vacuums():
    img, d = create_base()
    add_drop_shadow(d, 600, 780, 280, 45)
    # Round disc body
    d.ellipse([320, 360, 880, 760], fill="#FFFFFF", outline="#D1D5DB", width=6)
    # Front bumper edge
    d.arc([320, 360, 880, 760], start=170, end=370, fill="#9CA3AF", width=12)
    # Raised circular LiDAR laser turret
    d.ellipse([520, 460, 680, 600], fill="#F3F4F6", outline="#9CA3AF", width=4)
    d.ellipse([560, 495, 640, 565], fill="#EF4444")
    # Power and home buttons
    d.ellipse([580, 640, 620, 680], fill="#0284C7")
    return finalize_and_save(img, "cordless-robot-vacuums")

def draw_steam_cleaners():
    img, d = create_base()
    add_drop_shadow(d, 600, 920, 260, 35)
    # Yellow/black industrial steam body
    d.rounded_rectangle([380, 500, 820, 880], radius=34, fill="#FACC15", outline="#CA8A04", width=4)
    # Rear large wheels
    d.ellipse([340, 620, 440, 860], fill="#1F2937")
    d.ellipse([760, 620, 860, 860], fill="#1F2937")
    # Carry handle & safety cap
    d.rounded_rectangle([500, 360, 700, 520], radius=20, fill="#1F2937")
    d.ellipse([570, 420, 630, 480], fill="#DC2626")
    # Hose connection socket
    d.ellipse([550, 600, 650, 700], fill="#374151")
    return finalize_and_save(img, "steam-cleaners")

def draw_irons_garment_steamers():
    img, d = create_base()
    add_drop_shadow(d, 600, 880, 270, 35)
    # Modern steam iron body
    d.polygon([(300, 820), (840, 820), (900, 700), (440, 560)], fill="#8B5CF6", outline="#6D28D9", width=4)
    # Ergonomic handle
    d.polygon([(460, 560), (860, 680), (840, 480), (520, 420)], fill="#1F2937")
    # Ceramic soleplate base
    d.polygon([(260, 820), (860, 820), (880, 850), (280, 850)], fill="#D1D5DB", outline="#9CA3AF")
    # Steam boost button
    d.rounded_rectangle([560, 400, 620, 440], radius=6, fill="#A78BFA")
    # Temperature dial
    d.ellipse([600, 620, 700, 720], fill="#111827", outline="#4B5563", width=3)
    return finalize_and_save(img, "irons-garment-steamers")

# Category 6: Cooling, Heating & Air
def draw_air_conditioners_splits():
    img, d = create_base()
    add_drop_shadow(d, 600, 700, 320, 30)
    # Indoor wall unit
    d.rounded_rectangle([260, 360, 940, 660], radius=16, fill="#FFFFFF", outline="#E5E7EB", width=4)
    # Bottom airflow flap / louver
    d.rectangle([280, 610, 920, 650], fill="#F3F4F6", outline="#D1D5DB", width=2)
    # Digital temperature display
    d.rounded_rectangle([780, 440, 860, 500], radius=8, fill="#0F172A")
    d.rectangle([800, 460, 840, 480], fill="#38BDF8")
    # Intake grille slats on top
    for y in range(380, 420, 10):
        d.line([300, y, 900, y], fill="#E5E7EB", width=2)
    return finalize_and_save(img, "air-conditioners-splits")

def draw_fans():
    img, d = create_base()
    add_drop_shadow(d, 600, 1020, 220, 30)
    # Base stand
    d.ellipse([420, 960, 780, 1010], fill="#1F2937")
    # Telescopic pole
    d.rectangle([580, 620, 620, 970], fill="#9CA3AF", outline="#6B7280", width=2)
    # Control buttons on pole
    d.rounded_rectangle([560, 720, 640, 840], radius=8, fill="#374151")
    # Fan head guard mesh
    d.ellipse([360, 200, 840, 680], outline="#6B7280", width=6)
    # 5 translucent blades
    d.ellipse([560, 400, 640, 480], fill="#1F2937")
    for angle in [0, 72, 144, 216, 288]:
        d.arc([400, 240, 800, 640], start=angle, end=angle + 50, fill="#38BDF8", width=36)
    return finalize_and_save(img, "fans")

def draw_electric_heaters():
    img, d = create_base()
    add_drop_shadow(d, 600, 920, 260, 35)
    # Space heater housing
    d.rounded_rectangle([340, 360, 860, 880], radius=20, fill="#DC2626", outline="#991B1B", width=4)
    # Safety mesh grille
    d.rounded_rectangle([380, 420, 820, 780], radius=12, fill="#1F2937")
    # 4 glowing quartz heating elements
    for y in [480, 560, 640, 720]:
        d.line([420, y, 780, y], fill="#F97316", width=12)
        d.line([420, y, 780, y], fill="#FEF08A", width=4)
    # Control switches on base
    for x in [520, 600, 680]:
        d.rectangle([x, 810, x + 30, 850], fill="#FFFFFF")
    return finalize_and_save(img, "electric-heaters")

def draw_air_purifiers():
    img, d = create_base()
    add_drop_shadow(d, 600, 1000, 200, 35)
    # Minimalist white tower
    d.rounded_rectangle([420, 260, 780, 980], radius=24, fill="#FFFFFF", outline="#E5E7EB", width=4)
    # Circular OLED display
    d.ellipse([540, 340, 660, 460], fill="#0F172A", outline="#10B981", width=4)
    d.rectangle([580, 390, 620, 410], fill="#10B981")
    # Air intake perforations
    for y in range(540, 920, 25):
        for x in range(450, 750, 25):
            d.ellipse([x, y, x + 6, y + 6], fill="#D1D5DB")
    return finalize_and_save(img, "air-purifiers")

def draw_humidifiers():
    img, d = create_base()
    add_drop_shadow(d, 600, 960, 210, 35)
    # Elegant humidifier cylinder
    d.rounded_rectangle([420, 320, 780, 920], radius=32, fill="#F0F9FF", outline="#BAE6FD", width=4)
    # Top mist outlet nozzle
    d.rounded_rectangle([560, 250, 640, 330], radius=12, fill="#38BDF8")
    # Water level viewing window
    d.rounded_rectangle([585, 420, 615, 780], radius=8, fill="#0284C7")
    # Touch power button
    d.ellipse([560, 820, 640, 880], fill="#0284C7", outline="#38BDF8", width=3)
    return finalize_and_save(img, "humidifiers")

# Category 7: TV & Audio
def draw_televisions():
    img, d = create_base()
    add_drop_shadow(d, 600, 860, 330, 30)
    # TV frame & screen
    d.rounded_rectangle([240, 260, 960, 780], radius=10, fill="#0F172A", outline="#1E293B", width=6)
    # Screen artwork / vivid colors
    d.rectangle([252, 272, 948, 768], fill="#1E1B4B")
    d.ellipse([400, 360, 800, 680], fill="#4338CA")
    d.ellipse([520, 420, 720, 620], fill="#06B6D4")
    # Slim stand feet
    d.polygon([(360, 780), (320, 850), (400, 850)], fill="#64748B")
    d.polygon([(840, 780), (800, 850), (880, 850)], fill="#64748B")
    return finalize_and_save(img, "televisions")

def draw_soundbars_home_audio():
    img, d = create_base()
    add_drop_shadow(d, 600, 880, 320, 30)
    # Long sleek soundbar
    d.rounded_rectangle([220, 620, 980, 740], radius=16, fill="#1F2937", outline="#111827", width=4)
    # Fabric speaker grille pattern
    for x in range(260, 940, 20):
        d.line([x, 635, x, 725], fill="#374151", width=2)
    # Companion wireless subwoofer block
    d.rounded_rectangle([320, 320, 520, 600], radius=14, fill="#111827", outline="#374151", width=4)
    d.ellipse([360, 380, 480, 500], fill="#1F2937", outline="#4B5563", width=4)
    return finalize_and_save(img, "soundbars-home-audio")

# Category 8: Personal Care Appliances
def draw_hair_dryers():
    img, d = create_base()
    add_drop_shadow(d, 600, 920, 240, 35)
    # Hair dryer barrel
    d.rounded_rectangle([340, 340, 740, 540], radius=24, fill="#DB2777", outline="#9D174D", width=4)
    # Concentrator nozzle front
    d.polygon([(340, 390), (260, 410), (260, 470), (340, 490)], fill="#1F2937")
    # Rear air filter mesh
    d.ellipse([700, 360, 760, 520], fill="#1F2937", outline="#4B5563", width=3)
    # Ergonomic handle
    d.polygon([(560, 520), (660, 520), (620, 880), (540, 880)], fill="#1F2937")
    # Heat/Speed toggle switches
    d.rounded_rectangle([565, 580, 595, 630], radius=4, fill="#F43F5E")
    d.rounded_rectangle([560, 660, 590, 710], radius=4, fill="#9CA3AF")
    return finalize_and_save(img, "hair-dryers")

def draw_electric_shavers():
    img, d = create_base()
    add_drop_shadow(d, 600, 960, 180, 35)
    # Ergonomic curved handle
    d.rounded_rectangle([460, 440, 740, 940], radius=36, fill="#0F172A", outline="#1E293B", width=4)
    # Blue rubberized grip side panels
    d.rounded_rectangle([440, 540, 470, 820], radius=10, fill="#0284C7")
    d.rounded_rectangle([730, 540, 760, 820], radius=10, fill="#0284C7")
    # Power button & battery LED
    d.ellipse([570, 560, 630, 620], fill="#38BDF8")
    d.rectangle([585, 720, 615, 780], fill="#10B981")
    # Rotary 3-head shaver top
    d.polygon([(440, 450), (760, 450), (700, 240), (500, 240)], fill="#9CA3AF", outline="#6B7280", width=4)
    d.ellipse([540, 250, 660, 350], fill="#E5E7EB", outline="#374151", width=3)
    d.ellipse([470, 330, 580, 430], fill="#E5E7EB", outline="#374151", width=3)
    d.ellipse([620, 330, 730, 430], fill="#E5E7EB", outline="#374151", width=3)
    return finalize_and_save(img, "electric-shavers")

def draw_hair_straighteners():
    img, d = create_base()
    add_drop_shadow(d, 600, 860, 280, 30)
    # Straightener dual tongs
    d.polygon([(280, 460), (840, 540), (840, 620), (280, 540)], fill="#1F2937")
    d.polygon([(280, 640), (840, 560), (840, 640), (280, 720)], fill="#1F2937")
    # Ceramic floating heat plates (Gold/Rose Gold)
    d.polygon([(300, 490), (600, 530), (600, 560), (300, 520)], fill="#F59E0B")
    d.polygon([(300, 660), (600, 620), (600, 590), (300, 630)], fill="#F59E0B")
    # Digital temperature display
    d.rounded_rectangle([680, 570, 760, 610], radius=4, fill="#0F172A")
    d.rectangle([700, 585, 740, 595], fill="#38BDF8")
    # Hinge swivel cord at back
    d.ellipse([810, 560, 870, 620], fill="#374151")
    return finalize_and_save(img, "hair-straighteners")

def main():
    generators = [
        draw_refrigerators_freezers,
        draw_washing_machines,
        draw_dishwashers,
        draw_cookers_stoves,
        draw_built_in_ovens,
        draw_range_hoods,
        draw_microwaves,
        draw_air_fryers,
        draw_toaster_ovens,
        draw_rice_pressure_cookers,
        draw_electric_grills_sandwich_makers,
        draw_food_processors_blenders,
        draw_juicers,
        draw_meat_grinders,
        draw_mixers,
        draw_choppers_grinders,
        draw_tea_makers_electric_kettles,
        draw_coffee_espresso_machines,
        draw_coffee_grinders,
        draw_bread_toasters,
        draw_water_dispensers,
        draw_vacuum_cleaners,
        draw_cordless_robot_vacuums,
        draw_steam_cleaners,
        draw_irons_garment_steamers,
        draw_air_conditioners_splits,
        draw_fans,
        draw_electric_heaters,
        draw_air_purifiers,
        draw_humidifiers,
        draw_televisions,
        draw_soundbars_home_audio,
        draw_hair_dryers,
        draw_electric_shavers,
        draw_hair_straighteners,
    ]
    print(f"Generating {len(generators)} high-quality appliance product images...")
    for gen in generators:
        gen()
    print("All images generated successfully!")

if __name__ == "__main__":
    main()
