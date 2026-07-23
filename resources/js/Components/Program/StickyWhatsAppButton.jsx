import { usePage } from "@inertiajs/react";
import { MessageCircle } from "lucide-react";

export default function StickyWhatsAppButton({ message }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const phone = "628129928939"; // Ganti dengan nomor WhatsApp

    const defaultMessage = isEn
        ? "Hello DIGESTEX, I would like to learn more about the DIGESTEX Digital Directory & Visibility Program 2026."
        : "Halo DIGESTEX, saya ingin mengetahui lebih lanjut mengenai DIGESTEX Digital Directory & Visibility Program 2026.";

    const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(
        message || defaultMessage,
    )}`;

    return (
        <div
            className="
                fixed
                bottom-6
                left-6
                z-50
            "
        >
            <a
                href={whatsappUrl}
                target="_blank"
                rel="noopener noreferrer"
                className="
                    group
                    inline-flex
                    items-center
                    gap-3
                    rounded-full
                    bg-green-500
                    px-6
                    py-4
                    font-bold
                    text-white
                    shadow-2xl
                    transition-all
                    duration-300

                    hover:-translate-y-1
                    hover:bg-green-600
                "
            >
                <MessageCircle
                    className="
                        h-5
                        w-5
                        transition-transform
                        group-hover:scale-110
                    "
                />

                <div className="hidden md:block">
                    {isEn ? "Chat via WhatsApp" : "Chat via WhatsApp"}
                </div>
            </a>
        </div>
    );
}
