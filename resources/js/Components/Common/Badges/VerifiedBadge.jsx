import { BadgeCheck } from "lucide-react";

export default function VerifiedBadge() {
    return (
        <span
            className="

                inline-flex

                items-center

                gap-2

                rounded-full

                bg-blue-100

                px-3

                py-1

                text-xs

                font-bold

                text-blue-700

            "
        >
            <BadgeCheck size={14} />
            VERIFIED
        </span>
    );
}
