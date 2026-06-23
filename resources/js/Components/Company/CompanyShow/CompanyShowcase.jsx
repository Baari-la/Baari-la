export default function CompanyShowcase({ company }) {
    const hasImages = company?.images?.length > 0;
    const hasProducts = company?.products?.length > 0;

    if (!hasImages && !hasProducts) {
        return null;
    }

    const featuredImage = company.images?.find((image) => image.is_featured);

    const primaryProducts =
        company.products?.filter((product) => product.is_primary === 1)
            .length || 0;

    return (
        <section
            className="
            relative
            overflow-hidden
            rounded-[40px]
            border
            border-white/10
            bg-white/5
            p-10
            mb-8
        "
        >
            {/* BACKGROUND EFFECT */}
            <div
                className="
                absolute
                top-0
                left-0
                h-72
                w-72
                rounded-full
                bg-blue-500/10
                blur-3xl
            "
            />

            <div className="relative z-10">
                {/* HEADER */}
                <div className="mb-10">
                    <div
                        className="
                        text-xs
                        font-black
                        uppercase
                        tracking-[0.35em]
                        text-blue-400
                        mb-3
                    "
                    >
                        Product Portfolio
                    </div>

                    <h2 className="text-3xl font-black text-white">
                        Factory Showcase
                    </h2>

                    <p className="text-gray-400 mt-3 max-w-3xl">
                        Manufactured products, factory assets, production
                        environment, and visual portfolio.
                    </p>
                </div>

                {/* SUMMARY */}
                <div className="grid md:grid-cols-3 gap-5 mb-10">
                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div
                            className="
                            text-[10px]
                            uppercase
                            tracking-[0.3em]
                            text-gray-500
                            font-black
                            mb-3
                        "
                        >
                            Factory Images
                        </div>

                        <div className="text-4xl font-black text-blue-400">
                            {company.images?.length || 0}
                        </div>
                    </div>

                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div
                            className="
                            text-[10px]
                            uppercase
                            tracking-[0.3em]
                            text-gray-500
                            font-black
                            mb-3
                        "
                        >
                            Products
                        </div>

                        <div className="text-4xl font-black text-white">
                            {company.products?.length || 0}
                        </div>
                    </div>

                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div
                            className="
                            text-[10px]
                            uppercase
                            tracking-[0.3em]
                            text-gray-500
                            font-black
                            mb-3
                        "
                        >
                            Primary Products
                        </div>

                        <div className="text-4xl font-black text-yellow-400">
                            {primaryProducts}
                        </div>
                    </div>
                </div>

                {/* FEATURED IMAGE */}
                {featuredImage && (
                    <div className="mb-12">
                        <div
                            className="
                            text-xs
                            font-black
                            uppercase
                            tracking-[0.3em]
                            text-white
                            mb-5
                        "
                        >
                            Featured Factory Image
                        </div>

                        <div
                            className="
                            overflow-hidden
                            rounded-[32px]
                            border
                            border-white/10
                        "
                        >
                            <img
                                src={
                                    featuredImage.image_url
                                        ? featuredImage.image_url
                                        : `/storage/${featuredImage.image_path}`
                                }
                                alt={featuredImage.caption || ""}
                                className="
                                w-full
                                h-[420px]
                                object-cover
                            "
                            />
                        </div>
                        {featuredImage.caption && (
                            <div className="mt-4 text-gray-400">
                                {featuredImage.caption}
                            </div>
                        )}
                    </div>
                )}

                {/* GALLERY */}
                {hasImages && (
                    <div className="mb-12">
                        <div className="flex items-center justify-between mb-5">
                            <div
                                className="
                                text-xs
                                font-black
                                uppercase
                                tracking-[0.3em]
                                text-white
                            "
                            >
                                Factory Gallery
                            </div>

                            <div
                                className="
                                text-[10px]
                                uppercase
                                tracking-widest
                                text-gray-500
                            "
                            >
                                {company.images.length} Images
                            </div>
                        </div>

                        <div
                            className="
                            columns-1
                            md:columns-3
                            gap-5
                            space-y-5
                        "
                        >
                            {company.images
                                .filter(
                                    (image) => image.id !== featuredImage?.id,
                                )
                                .map((image) => (
                                    <div
                                        key={image.id}
                                        className="
                                        break-inside-avoid
                                        overflow-hidden
                                        rounded-[30px]
                                        border
                                        border-white/10
                                        bg-white/5
                                    "
                                    >
                                        <img
                                            src={
                                                image.image_url
                                                    ? image.image_url
                                                    : `/storage/${image.image_path}`
                                            }
                                            alt={
                                                image.caption || "Factory Image"
                                            }
                                            className="
                                            w-full
                                            object-cover
                                            hover:scale-105
                                            transition-all
                                            duration-700
                                        "
                                        />

                                        <div className="p-5">
                                            {image.image_type && (
                                                <div
                                                    className="
                                                    text-[9px]
                                                    uppercase
                                                    tracking-[0.3em]
                                                    text-blue-400
                                                    font-black
                                                    mb-3
                                                "
                                                >
                                                    {image.image_type}
                                                </div>
                                            )}

                                            {image.caption && (
                                                <p
                                                    className="
                                                    text-sm
                                                    text-gray-300
                                                "
                                                >
                                                    {image.caption}
                                                </p>
                                            )}
                                        </div>
                                    </div>
                                ))}
                        </div>
                    </div>
                )}

                {/* PRODUCTS */}
                {hasProducts && (
                    <div>
                        <div
                            className="
                            text-xs
                            font-black
                            uppercase
                            tracking-[0.3em]
                            text-white
                            mb-6
                        "
                        >
                            Product Portfolio
                        </div>

                        <div className="grid md:grid-cols-2 gap-6">
                            {company.products.map((product) => (
                                <div
                                    key={product.id}
                                    className="
                                    rounded-[32px]
                                    border
                                    border-white/10
                                    bg-gradient-to-br
                                    from-white/5
                                    to-white/[0.02]
                                    p-6
                                "
                                >
                                    <div className="flex justify-between items-start mb-4">
                                        <div>
                                            <h3
                                                className="
                                                text-xl
                                                font-black
                                                uppercase
                                                italic
                                                text-white
                                            "
                                            >
                                                {product.product_name}
                                            </h3>

                                            {product.category && (
                                                <div
                                                    className="
                                                    mt-2
                                                    text-[10px]
                                                    uppercase
                                                    tracking-widest
                                                    text-blue-400
                                                    font-black
                                                "
                                                >
                                                    {product.category}
                                                </div>
                                            )}
                                        </div>

                                        {product.is_primary === 1 && (
                                            <span
                                                className="
                                                px-3
                                                py-2
                                                rounded-full
                                                bg-yellow-500
                                                text-[#0a192f]
                                                text-[9px]
                                                uppercase
                                                font-black
                                            "
                                            >
                                                Primary
                                            </span>
                                        )}
                                    </div>

                                    {product.description && (
                                        <p
                                            className="
                                            text-sm
                                            text-gray-400
                                            leading-relaxed
                                            line-clamp-3
                                            mb-5
                                        "
                                        >
                                            {product.description}
                                        </p>
                                    )}

                                    <div className="flex flex-wrap gap-3">
                                        {product.hs_code && (
                                            <span
                                                className="
                                                px-3
                                                py-2
                                                rounded-full
                                                bg-yellow-500/10
                                                text-yellow-400
                                                text-[10px]
                                                font-black
                                                uppercase
                                            "
                                            >
                                                HS {product.hs_code}
                                            </span>
                                        )}

                                        {product.category && (
                                            <span
                                                className="
                                                px-3
                                                py-2
                                                rounded-full
                                                bg-blue-500/10
                                                text-blue-400
                                                text-[10px]
                                                font-black
                                                uppercase
                                            "
                                            >
                                                {product.category}
                                            </span>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </section>
    );
}
