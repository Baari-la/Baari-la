import AppLayout from "@/Layouts/WebsiteLayout";

import ExecutiveHeader from "./Components/ExecutiveHeader";
import SmartBusinessMatchingCard from "./Components/SmartBusinessMatchingCard";

export default function Index({ passport }) {
    return (
        <AppLayout>
            <div className="mx-auto max-w-7xl space-y-8 px-6 py-8">
                <ExecutiveHeader passport={passport} />

                <SmartBusinessMatchingCard matching={passport.matching} />
            </div>
        </AppLayout>
    );
}
