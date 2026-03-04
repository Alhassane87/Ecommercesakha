<?php

namespace App\Services;

use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\CategoryAttributeValue;

class CategoryAttributeService
{
    /**
     * Configure default attributes for a category.
     */
    public static function setDefaultAttributesForCategory(Category $category): void
    {
        $name = function_exists('mb_strtolower')
            ? mb_strtolower((string) $category->name, 'UTF-8')
            : strtolower((string) $category->name);

        if (str_contains($name, 'basket') || str_contains($name, 'chaussure') || str_contains($name, 'sneaker')) {
            self::createBasketAttributes($category);
            return;
        }

        if (
            str_contains($name, 'mode') ||
            str_contains($name, 'vetement') ||
            str_contains($name, 'vêtement') ||
            str_contains($name, 'pantalon') ||
            str_contains($name, 'pull') ||
            str_contains($name, 'tshirt') ||
            str_contains($name, 't-shirt')
        ) {
            self::createModeAttributes($category);
            return;
        }

        if (
            str_contains($name, 'telephone') ||
            str_contains($name, 'téléphone') ||
            str_contains($name, 'phone') ||
            str_contains($name, 'smartphone')
        ) {
            self::createPhoneAttributes($category);
            return;
        }

        if (
            str_contains($name, 'complement') ||
            str_contains($name, 'complément') ||
            str_contains($name, 'nutrition') ||
            str_contains($name, 'vitamine') ||
            str_contains($name, 'sante') ||
            str_contains($name, 'santé')
        ) {
            self::createSupplementAttributes($category);
            return;
        }

        if (
            str_contains($name, 'ordinateur') ||
            str_contains($name, 'computer') ||
            str_contains($name, 'laptop') ||
            str_contains($name, 'pc')
        ) {
            self::createComputerAttributes($category);
            return;
        }

        $iconToAttributes = [
            'fas fa-tshirt' => [
                'Taille' => ['S', 'M', 'L', 'XL', 'XXL'],
                'Couleur' => self::defaultColors(),
            ],
            'fas fa-shoe-prints' => [
                'Pointure' => ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46'],
                'Couleur' => self::defaultColors(),
            ],
            'fas fa-mobile-alt' => [
                'Modele' => self::defaultPhoneModels(),
                'Capacite' => ['32GB', '64GB', '128GB', '256GB', '512GB', '1TB'],
                'Couleur' => self::defaultColors(),
            ],
            'fas fa-laptop' => [
                'Processeur' => ['Intel i3', 'Intel i5', 'Intel i7', 'Intel i9', 'AMD Ryzen 5', 'AMD Ryzen 7', 'AMD Ryzen 9'],
                'RAM' => ['8GB', '16GB', '24GB', '32GB', '64GB'],
                'Stockage' => ['256GB SSD', '512GB SSD', '1TB SSD', '2TB SSD'],
                'Couleur' => self::defaultColors(),
            ],
            'fas fa-dumbbell' => [
                'Taille' => ['S', 'M', 'L', 'XL', 'XXL'],
                'Couleur' => self::defaultColors(),
            ],
            'fas fa-pills' => [
                'Format' => ['Gelules', 'Comprimes', 'Poudre', 'Liquide', 'Gummies'],
                'Saveur' => ['Neutre', 'Vanille', 'Chocolat', 'Fraise', 'Citron', 'Orange'],
                'Contenance' => ['30 unites', '60 unites', '90 unites', '120 unites', '500 g', '1 kg'],
            ],
        ];

        $icon = (string) ($category->icon ?? '');
        if ($icon !== '' && isset($iconToAttributes[$icon])) {
            self::applyDefaultAttributes($category, $iconToAttributes[$icon]);
        }
    }

    private static function createBasketAttributes(Category $category): void
    {
        self::applyDefaultAttributes($category, [
            'Pointure' => ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46'],
        ]);
        self::createCouleurAttribute($category, 2);
    }

    private static function createModeAttributes(Category $category): void
    {
        self::applyDefaultAttributes($category, [
            'Taille' => ['S', 'M', 'L', 'XL', 'XXL'],
        ]);
        self::createCouleurAttribute($category, 2);
    }

    private static function createPhoneAttributes(Category $category): void
    {
        self::applyDefaultAttributes($category, [
            'Modele' => self::defaultPhoneModels(),
            'Capacite' => ['32GB', '64GB', '128GB', '256GB', '512GB', '1TB'],
        ]);
        self::createCouleurAttribute($category, 3);
    }

    private static function createComputerAttributes(Category $category): void
    {
        self::applyDefaultAttributes($category, [
            'Modele' => ['Ultrabook', 'Gaming', 'Business', 'Workstation', '2-en-1'],
            'Processeur' => ['Intel i3', 'Intel i5', 'Intel i7', 'Intel i9', 'AMD Ryzen 5', 'AMD Ryzen 7', 'AMD Ryzen 9'],
            'RAM' => ['8GB', '16GB', '24GB', '32GB', '64GB'],
            'Stockage' => ['256GB SSD', '512GB SSD', '1TB SSD', '2TB SSD'],
        ]);
        self::createCouleurAttribute($category, 5);
    }

    private static function createSupplementAttributes(Category $category): void
    {
        self::applyDefaultAttributes($category, [
            'Format' => ['Gelules', 'Comprimes', 'Poudre', 'Liquide', 'Gummies'],
            'Saveur' => ['Neutre', 'Vanille', 'Chocolat', 'Fraise', 'Citron', 'Orange'],
            'Contenance' => ['30 unites', '60 unites', '90 unites', '120 unites', '500 g', '1 kg'],
        ]);
    }

    private static function applyDefaultAttributes(Category $category, array $attributes): void
    {
        $sortOrder = 1;

        foreach ($attributes as $attrName => $values) {
            $attribute = CategoryAttribute::firstOrCreate(
                [
                    'category_id' => $category->id,
                    'name' => $attrName,
                ],
                [
                    'type' => 'select',
                    'is_required' => true,
                    'sort_order' => $sortOrder,
                ]
            );

            if (!$attribute->wasRecentlyCreated && !$attribute->sort_order) {
                $attribute->update(['sort_order' => $sortOrder]);
            }

            foreach ($values as $index => $value) {
                if (is_array($value)) {
                    $rawValue = (string) ($value['value'] ?? '');
                    if ($rawValue === '') {
                        continue;
                    }

                    CategoryAttributeValue::firstOrCreate(
                        [
                            'category_attribute_id' => $attribute->id,
                            'value' => $rawValue,
                        ],
                        [
                            'display_value' => (string) ($value['display_value'] ?? $rawValue),
                            'color_code' => $value['color_code'] ?? null,
                            'sort_order' => $index,
                        ]
                    );
                } else {
                    $rawValue = trim((string) $value);
                    if ($rawValue === '') {
                        continue;
                    }

                    CategoryAttributeValue::firstOrCreate(
                        [
                            'category_attribute_id' => $attribute->id,
                            'value' => $rawValue,
                        ],
                        [
                            'display_value' => $rawValue,
                            'sort_order' => $index,
                        ]
                    );
                }
            }

            $sortOrder++;
        }
    }

    private static function createCouleurAttribute(Category $category, int $sortOrder = 2): void
    {
        $attribute = CategoryAttribute::firstOrCreate(
            [
                'category_id' => $category->id,
                'name' => 'Couleur',
            ],
            [
                'type' => 'select',
                'is_required' => true,
                'sort_order' => $sortOrder,
            ]
        );

        foreach (self::defaultColors() as $index => $color) {
            CategoryAttributeValue::firstOrCreate(
                [
                    'category_attribute_id' => $attribute->id,
                    'value' => $color['value'],
                ],
                [
                    'display_value' => $color['value'],
                    'color_code' => $color['color_code'] ?? null,
                    'sort_order' => $index,
                ]
            );
        }
    }

    private static function defaultPhoneModels(): array
    {
        return [
            'iPhone 13', 'iPhone 13 Pro', 'iPhone 13 Pro Max',
            'iPhone 14', 'iPhone 14 Plus', 'iPhone 14 Pro', 'iPhone 14 Pro Max',
            'iPhone 15', 'iPhone 15 Plus', 'iPhone 15 Pro', 'iPhone 15 Pro Max',
            'iPhone 16', 'iPhone 16 Plus', 'iPhone 16 Pro', 'iPhone 16 Pro Max',
            'Samsung Galaxy S23', 'Samsung Galaxy S23 Ultra',
            'Samsung Galaxy S24', 'Samsung Galaxy S24 Plus', 'Samsung Galaxy S24 Ultra',
            'Samsung Galaxy S25', 'Samsung Galaxy S25 Plus', 'Samsung Galaxy S25 Ultra',
            'Samsung Galaxy Z Flip 5', 'Samsung Galaxy Z Flip 6',
            'Samsung Galaxy Z Fold 5', 'Samsung Galaxy Z Fold 6',
            'Samsung Galaxy A15', 'Samsung Galaxy A25', 'Samsung Galaxy A35', 'Samsung Galaxy A55',
            'Google Pixel 8', 'Google Pixel 8 Pro', 'Google Pixel 9', 'Google Pixel 9 Pro',
            'Xiaomi Redmi Note 13', 'Xiaomi Redmi Note 14', 'Poco X6',
            'Tecno Camon 30', 'Tecno Phantom V Fold', 'Infinix Note 40',
        ];
    }

    private static function defaultColors(): array
    {
        return [
            ['value' => 'Noir', 'color_code' => '#000000'],
            ['value' => 'Blanc', 'color_code' => '#FFFFFF'],
            ['value' => 'Gris', 'color_code' => '#808080'],
            ['value' => 'Argent', 'color_code' => '#C0C0C0'],
            ['value' => 'Or', 'color_code' => '#D4AF37'],
            ['value' => 'Bleu', 'color_code' => '#1D4ED8'],
            ['value' => 'Bleu marine', 'color_code' => '#0F172A'],
            ['value' => 'Rouge', 'color_code' => '#DC2626'],
            ['value' => 'Vert', 'color_code' => '#16A34A'],
            ['value' => 'Jaune', 'color_code' => '#FACC15'],
            ['value' => 'Orange', 'color_code' => '#F97316'],
            ['value' => 'Rose', 'color_code' => '#EC4899'],
            ['value' => 'Violet', 'color_code' => '#8B5CF6'],
            ['value' => 'Turquoise', 'color_code' => '#14B8A6'],
            ['value' => 'Marron', 'color_code' => '#8B5A2B'],
            ['value' => 'Beige', 'color_code' => '#D6BC8A'],
        ];
    }
}
